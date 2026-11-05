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
            if (!Schema::hasColumn('surat_keluars', 'tujuan_ppk')) {
                $table->string('tujuan_ppk')->nullable()->after('tujuan_unit');
            }
            if (!Schema::hasColumn('surat_keluars', 'tujuan_rahasia_role')) {
                $table->string('tujuan_rahasia_role')->nullable()->after('tujuan_penandatangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluars', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('surat_keluars', 'tujuan_ppk')) {
                $drop[] = 'tujuan_ppk';
            }
            if (Schema::hasColumn('surat_keluars', 'tujuan_rahasia_role')) {
                $drop[] = 'tujuan_rahasia_role';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};

