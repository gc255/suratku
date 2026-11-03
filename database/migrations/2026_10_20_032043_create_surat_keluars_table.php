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
        Schema::create('surat_keluars', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('file_draft'); // Menyimpan nama file draft awal
        $table->string('file_final')->nullable(); // File setelah di-TTD (boleh kosong di awal)
        $table->foreignId('pembuat_id')->constrained('users'); // Relasi ke tabel users (siapa yang buat)
        $table->string('status'); // Contoh: DIBUAT, MENUNGGU PARAF KABAG, dll
        $table->timestamps(); // Otomatis membuat kolom created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
