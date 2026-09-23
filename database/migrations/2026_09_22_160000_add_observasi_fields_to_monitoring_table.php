<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            if (!Schema::hasColumn('monitoring', 'file_observasi')) {
                $table->string('file_observasi')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('monitoring', 'kesesuaian_kompetensi')) {
                $table->string('kesesuaian_kompetensi')->nullable()->default('sesuai')->after('file_observasi');
            }
            if (!Schema::hasColumn('monitoring', 'kedisiplinan_siswa')) {
                $table->string('kedisiplinan_siswa')->nullable()->default('baik')->after('kesesuaian_kompetensi');
            }
            if (!Schema::hasColumn('monitoring', 'kendala_observasi')) {
                $table->text('kendala_observasi')->nullable()->after('kedisiplinan_siswa');
            }
            if (!Schema::hasColumn('monitoring', 'saran_dudi')) {
                $table->text('saran_dudi')->nullable()->after('kendala_observasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            $table->dropColumn([
                'file_observasi',
                'kesesuaian_kompetensi',
                'kedisiplinan_siswa',
                'kendala_observasi',
                'saran_dudi'
            ]);
        });
    }
};
