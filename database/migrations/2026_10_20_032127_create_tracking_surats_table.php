<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracking_surats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('surat_keluar_id')->constrained('surat_keluars')->onDelete('cascade'); 
        // Relasi ke tabel surat (jika surat dihapus, log tracking juga terhapus)
        
        $table->foreignId('aktor_id')->constrained('users'); // Siapa yang melakukan aksi (Kabag, Wadir, dll)
        $table->string('aksi'); // Contoh: "Diparaf", "Ditolak", "Diarsipkan"
        $table->text('catatan')->nullable(); // Alasan jika ditolak/direvisi (boleh kosong)
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_surats');
    }
};
