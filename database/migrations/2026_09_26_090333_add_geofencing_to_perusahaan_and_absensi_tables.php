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
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->string('latitude', 50)->nullable()->after('alamat');
            $table->string('longitude', 50)->nullable()->after('latitude');
            $table->unsignedInteger('radius_meter')->default(150)->after('longitude');
        });

        Schema::table('absensi_pkl', function (Blueprint $table) {
            $table->unsignedInteger('jarak_masuk_meter')->nullable()->after('lokasi_masuk');
            $table->string('status_lokasi_masuk', 30)->nullable()->after('jarak_masuk_meter'); // dalam_radius, luar_radius, tanpa_gps
            $table->unsignedInteger('jarak_keluar_meter')->nullable()->after('lokasi_keluar');
            $table->string('status_lokasi_keluar', 30)->nullable()->after('jarak_keluar_meter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter']);
        });

        Schema::table('absensi_pkl', function (Blueprint $table) {
            $table->dropColumn(['jarak_masuk_meter', 'status_lokasi_masuk', 'jarak_keluar_meter', 'status_lokasi_keluar']);
        });
    }
};
