<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload File Final (Telah Di-TTD)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4">
                    <h3 class="font-bold text-blue-800">Instruksi:</h3>
                    <p class="text-sm text-blue-700">Pastikan file yang diunggah adalah dokumen <strong>{{ $surat->judul }}</strong> yang telah ditandatangani oleh {{ $surat->tujuan_penandatangan_label }}, diberi stempel, dan formatnya adalah PDF.</p>
                </div>

                <form action="{{ route('surat.simpanFinal', $surat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Nomor Surat Resmi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nomor_surat" 
                               value="{{ old('nomor_surat', $surat->nomor_surat) }}" 
                               required 
                               placeholder="Contoh: 005/PU/TU.02.01/2026" 
                               class="shadow border rounded w-full py-2 px-3 text-gray-700 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Masukkan nomor surat resmi yang tertera pada berkas fisik ber-TTD.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File Final (.pdf) <span class="text-red-500">*</span></label>
                        <input type="file" name="file_final" class="shadow border rounded w-full py-2 px-3 text-gray-700" required accept=".pdf">
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 underline">Batal</a>
                        <button type="submit" class="btn-aksi-teal font-bold py-2.5 px-5 rounded-md shadow text-xs">
                            Upload & Arsipkan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
