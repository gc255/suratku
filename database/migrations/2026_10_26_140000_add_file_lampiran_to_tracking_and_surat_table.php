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
        Schema::table('tracking_surats', function (Blueprint $table) {
            $table->string('file_lampiran')->nullable()->after('catatan');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->string('lampiran_revisi_terakhir')->nullable()->after('catatan_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracking_surats', function (Blueprint $table) {
            $table->dropColumn('file_lampiran');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn('lampiran_revisi_terakhir');
        });
    }
};
