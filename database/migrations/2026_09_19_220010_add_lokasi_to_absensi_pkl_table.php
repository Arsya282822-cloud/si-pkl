<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_pkl', function (Blueprint $table) {
            $table->string('lokasi_masuk')->nullable()->after('jam_masuk');
            $table->string('lokasi_keluar')->nullable()->after('jam_keluar');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_pkl', function (Blueprint $table) {
            $table->dropColumn(['lokasi_masuk', 'lokasi_keluar']);
        });
    }
};
