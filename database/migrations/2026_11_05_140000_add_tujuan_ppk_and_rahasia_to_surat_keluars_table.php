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
            $table->string('tujuan_ppk')->nullable()->after('tujuan_unit');
            $table->string('tujuan_rahasia_role')->nullable()->after('tujuan_penandatangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn(['tujuan_ppk', 'tujuan_rahasia_role']);
        });
    }
};

