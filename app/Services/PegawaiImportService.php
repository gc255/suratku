<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use ZipArchive;
use SimpleXMLElement;
use DOMDocument;

class PegawaiImportService
{
    /**
     * Membaca file Excel (.xls / .xlsx) atau CSV dan mengonversinya menjadi array data pegawai
     */
    public function parseFile(string $filePath, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        if ($extension === 'xls') {
            return $this->parseXls($filePath);
        }

        return $this->parseCsv($filePath);
    }

    /**
     * Parsing file .xls (Mendukung XML SpreadsheetML, HTML Table, atau fallback)
     */
    public function parseXls(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        // Cek jika ternyata file .xlsx (ZIP archive) yang dinamai .xls
        if (str_starts_with($content, "PK\x03\x04")) {
            return $this->parseXlsx($filePath);
        }

        // Cek jika format XML Spreadsheet 2003 (SpreadsheetML)
        if (str_contains($content, 'urn:schemas-microsoft-com:office:spreadsheet') || str_contains($content, '<?mso-application progid="Excel.Sheet"?>')) {
            $parsed = $this->parseXmlSpreadsheet($content);
            if (!empty($parsed)) {
                return $parsed;
            }
        }

        // Cek jika format HTML table (Excel HTML export)
        if (str_contains(strtolower($content), '<table')) {
            $parsed = $this->parseHtmlTable($content);
            if (!empty($parsed)) {
                return $parsed;
            }
        }

        // Fallback jika berupa CSV
        return $this->parseCsv($filePath);
    }

    /**
     * Parsing format XML Spreadsheet 2003 (SpreadsheetML)
     */
    protected function parseXmlSpreadsheet(string $content): array
    {
        $xml = @simplexml_load_string($content);
        if (!$xml) {
            return [];
        }

        $xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
        $xmlRows = $xml->xpath('//ss:Row');
        if (empty($xmlRows)) {
            return [];
        }

        $rawRows = [];
        foreach ($xmlRows as $row) {
            $rowData = [];
            foreach ($row->xpath('ss:Cell') as $cell) {
                $data = $cell->xpath('ss:Data');
                $rowData[] = isset($data[0]) ? trim((string)$data[0]) : '';
            }
            if (!empty(array_filter($rowData))) {
                $rawRows[] = $rowData;
            }
        }

        return $this->mapRowsWithHeaders($rawRows);
    }

    /**
     * Parsing format HTML table (.xls format)
     */
    protected function parseHtmlTable(string $content): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        $rawRows = [];

        foreach ($dom->getElementsByTagName('tr') as $tr) {
            $rowData = [];
            foreach ($tr->childNodes as $cell) {
                if (in_array(strtolower($cell->nodeName), ['td', 'th'])) {
                    $rowData[] = trim($cell->textContent);
                }
            }
            if (!empty(array_filter($rowData))) {
                $rawRows[] = $rowData;
            }
        }

        return $this->mapRowsWithHeaders($rawRows);
    }

    /**
     * Parsing file CSV / TSV dengan auto-detect delimiter dan pembersihan UTF-8 BOM
     */
    public function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return [];
        }

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $firstLine = strtok($content, "\r\n");
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
            $delimiter = "\t";
        }

        $lines = preg_split('/[\r\n]+/', trim($content));
        if (empty($lines)) {
            return [];
        }

        $rows = [];
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $row = str_getcsv($line, $delimiter);
            $rows[] = array_map('trim', $row);
        }

        return $this->mapRowsWithHeaders($rows);
    }

    /**
     * Parsing file Excel .xlsx secara native menggunakan ZipArchive dan XML reader
     */
    public function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            return $this->parseCsv($filePath);
        }

        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string)$r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $xml = @simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rawRows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $type = (string)$cell['t'];
                $val = (string)$cell->v;

                if ($type === 's' && isset($sharedStrings[(int)$val])) {
                    $val = $sharedStrings[(int)$val];
                }

                $rowData[] = trim($val);
            }
            $rawRows[] = $rowData;
        }

        return $this->mapRowsWithHeaders($rawRows);
    }

    /**
     * Memetakan header baris pertama ke field sistem:
     * NAMA, NIP/NIK/NIM, Nomor Whatsapp
     */
    protected function mapRowsWithHeaders(array $rows): array
    {
        if (count($rows) < 2) {
            return [];
        }

        $headers = array_shift($rows);
        $headerMap = [];

        foreach ($headers as $index => $header) {
            $clean = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $header)));
            
            if (in_array($clean, ['nama', 'namalengkap', 'name', 'fullname', 'namapegawai'])) {
                $headerMap['name'] = $index;
            } elseif (in_array($clean, ['nip', 'nik', 'nim', 'nipnik', 'nipniknim', 'nomorinduk', 'noinduk', 'identitas'])) {
                $headerMap['nip'] = $index;
            } elseif (in_array($clean, ['nomorwhatsapp', 'nomorwa', 'nowa', 'whatsapp', 'wa', 'nohp', 'telepon', 'phone'])) {
                $headerMap['no_wa'] = $index;
            }
        }

        // Default urutan standar jika header tidak otomatis cocok: Kolom 0 = Nama, Kolom 1 = NIP, Kolom 2 = No WA
        if (!isset($headerMap['name'])) $headerMap['name'] = 0;
        if (!isset($headerMap['nip']) && isset($headers[1])) $headerMap['nip'] = 1;
        if (!isset($headerMap['no_wa']) && isset($headers[2])) $headerMap['no_wa'] = 2;

        $mappedData = [];
        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                continue;
            }

            $name = $row[$headerMap['name']] ?? '';
            if (empty($name)) {
                continue;
            }

            $nip = isset($headerMap['nip']) ? ($row[$headerMap['nip']] ?? '') : '';
            $noWa = isset($headerMap['no_wa']) ? ($row[$headerMap['no_wa']] ?? '') : '';

            $mappedData[] = [
                'name' => $name,
                'nip' => $nip,
                'no_wa' => $noWa,
            ];
        }

        return $mappedData;
    }

    /**
     * Memproses dan menyimpan data pegawai ke database
     */
    public function import(array $records): array
    {
        $importedCount = 0;
        $updatedCount = 0;
        $errors = [];

        foreach ($records as $index => $rec) {
            $rowNum = $index + 2;
            $name = trim($rec['name'] ?? '');
            $nip = trim($rec['nip'] ?? '');
            $noWa = trim($rec['no_wa'] ?? '');

            if (empty($name)) {
                $errors[] = "Baris $rowNum: Nama pegawai tidak boleh kosong.";
                continue;
            }

            // Standarisasi nomor WA (misal awalan 08 / 62)
            $cleanWa = preg_replace('/[^0-9]/', '', $noWa);

            // Role default adalah 'penyusun' (seluruh pegawai dapat menyusun surat)
            $role = 'penyusun';

            // Password default menggunakan NIP atau 'pegawai123'
            $passwordPlain = !empty($nip) ? $nip : 'pegawai123';

            // Cek apakah user sudah ada berdasarkan NIP/NIK/NIM atau Nama
            $existingUser = null;
            if (!empty($nip)) {
                $existingUser = User::where('nip', $nip)->first();
            }
            if (!$existingUser) {
                $existingUser = User::where('name', $name)->first();
            }

            if ($existingUser) {
                // Perbarui data yang ada
                $updateData = [
                    'name' => $name,
                    'no_wa' => !empty($noWa) ? $noWa : $existingUser->no_wa,
                ];
                if (!empty($nip)) {
                    $updateData['nip'] = $nip;
                    // Pastikan password default ter-update jika belum diubah
                    $updateData['password'] = Hash::make($passwordPlain);
                }
                $existingUser->update($updateData);
                $updatedCount++;
            } else {
                // Buat user baru
                User::create([
                    'name' => $name,
                    'nip' => !empty($nip) ? $nip : null,
                    'no_wa' => !empty($noWa) ? $noWa : null,
                    'email' => null,
                    'role' => $role,
                    'password' => Hash::make($passwordPlain),
                ]);
                $importedCount++;
            }
        }

        return [
            'success' => true,
            'imported' => $importedCount,
            'updated' => $updatedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Menghasilkan konten file format Excel .xls resmi (XML SpreadsheetML)
     * Kolom: NAMA, NIP/NIK/NIM, Nomor Whatsapp
     * Semua cell diformat sebagai String/Text agar angka 0 di depan (08...) dan digit panjang NIP tidak rusak di Excel
     */
    public function getTemplateXlsContent(): string
    {
        $examples = [
            [
                'name' => 'Ahmad Fauzi, S.Kom',
                'nip' => '198501152010011005',
                'no_wa' => '081234567890',
            ],
            [
                'name' => 'Siti Rahmawati, M.Pd',
                'nip' => '199003202014022003',
                'no_wa' => '085712345678',
            ],
            [
                'name' => 'Budi Santoso',
                'nip' => '3302198705120001',
                'no_wa' => '081398765432',
            ],
            [
                'name' => 'Dewi Lestari, S.E',
                'nip' => '2023010025',
                'no_wa' => '082133445566',
            ],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= ' <Styles>' . "\n";
        $xml .= '  <Style ss:ID="Header">' . "\n";
        $xml .= '   <Font ss:Bold="1" ss:Color="#FFFFFF" ss:FontName="Calibri" ss:Size="11"/>' . "\n";
        $xml .= '   <Interior ss:Color="#2563EB" ss:Pattern="Solid"/>' . "\n";
        $xml .= '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n";
        $xml .= '   <Borders>' . "\n";
        $xml .= '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>' . "\n";
        $xml .= '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>' . "\n";
        $xml .= '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>' . "\n";
        $xml .= '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1D4ED8"/>' . "\n";
        $xml .= '   </Borders>' . "\n";
        $xml .= '  </Style>' . "\n";
        $xml .= '  <Style ss:ID="TextCell">' . "\n";
        $xml .= '   <NumberFormat ss:Format="@"/>' . "\n";
        $xml .= '   <Font ss:FontName="Calibri" ss:Size="11"/>' . "\n";
        $xml .= '   <Alignment ss:Vertical="Center"/>' . "\n";
        $xml .= '   <Borders>' . "\n";
        $xml .= '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/>' . "\n";
        $xml .= '   </Borders>' . "\n";
        $xml .= '  </Style>' . "\n";
        $xml .= ' </Styles>' . "\n";
        $xml .= ' <Worksheet ss:Name="Data Pegawai">' . "\n";
        $xml .= '  <Table>' . "\n";
        $xml .= '   <Column ss:Width="220"/>' . "\n";
        $xml .= '   <Column ss:Width="180"/>' . "\n";
        $xml .= '   <Column ss:Width="160"/>' . "\n";
        
        // Header Row
        $xml .= '   <Row ss:Height="24">' . "\n";
        $xml .= '    <Cell ss:StyleID="Header"><Data ss:Type="String">NAMA</Data></Cell>' . "\n";
        $xml .= '    <Cell ss:StyleID="Header"><Data ss:Type="String">NIP/NIK/NIM</Data></Cell>' . "\n";
        $xml .= '    <Cell ss:StyleID="Header"><Data ss:Type="String">Nomor Whatsapp</Data></Cell>' . "\n";
        $xml .= '   </Row>' . "\n";

        // Example Rows
        foreach ($examples as $item) {
            $xml .= '   <Row ss:Height="20">' . "\n";
            $xml .= '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">' . htmlspecialchars($item['name'], ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
            $xml .= '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">' . htmlspecialchars($item['nip'], ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
            $xml .= '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">' . htmlspecialchars($item['no_wa'], ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
            $xml .= '   </Row>' . "\n";
        }

        $xml .= '  </Table>' . "\n";
        $xml .= ' </Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return $xml;
    }
}
