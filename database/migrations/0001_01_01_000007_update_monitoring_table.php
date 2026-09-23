<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            $table->foreignId('perusahaan_id')->nullable()->after('guru_id')->constrained('perusahaan')->nullOnDelete();
            $table->date('tanggal_kunjungan')->nullable()->after('perusahaan_id');
            $table->text('catatan')->nullable()->after('tanggal_kunjungan');
            $table->string('foto')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropColumn(['perusahaan_id', 'tanggal_kunjungan', 'catatan', 'foto']);
        });
    }
};
