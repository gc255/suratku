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
            $table->string('tujuan_kasubbag')->nullable()->after('jenis_surat_id');
            $table->string('tujuan_wadir')->nullable()->after('tujuan_kasubbag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn(['tujuan_kasubbag', 'tujuan_wadir']);
        });
    }
};
