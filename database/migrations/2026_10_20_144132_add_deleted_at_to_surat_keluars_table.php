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
    Schema::table('surat_keluars', function (Blueprint $table) {
        $table->softDeletes(); // Ini otomatis membuat kolom 'deleted_at'
    });
}

public function down(): void
{
    Schema::table('surat_keluars', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
