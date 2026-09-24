<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lembar_observasi')) {
            Schema::create('lembar_observasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penempatan_id')->nullable()->constrained('penempatan')->onDelete('cascade');
                $table->foreignId('guru_id')->nullable()->constrained('guru')->onDelete('set null');
                $table->foreignId('perusahaan_id')->nullable()->constrained('perusahaan')->onDelete('set null');
                $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null');
                
                // I. Data Institusi
                $table->string('nama_institusi')->nullable();
                $table->text('alamat_institusi')->nullable();
                $table->string('telp_institusi')->nullable();

                // II. Data Pimpinan
                $table->string('nama_pimpinan')->nullable();
                $table->string('nip_pimpinan')->nullable();
                $table->string('jabatan_pimpinan')->nullable();

                // III. Data Instruktur
                $table->string('nama_instruktur')->nullable();
                $table->string('nip_instruktur')->nullable();
                $table->string('jabatan_instruktur')->nullable();

                // IV. Data Murid
                $table->string('nama_murid')->nullable();
                $table->string('konsentrasi_keahlian')->nullable();

                // JSON Assessment Sections
                $table->json('data_softskills')->nullable();
                $table->json('data_kompetensi_teknis')->nullable();
                $table->json('data_kompetensi_baru')->nullable();
                $table->json('data_analisis_usaha')->nullable();

                // Dates & Signatures Metadata
                $table->date('tgl_observasi_1')->nullable();
                $table->date('tgl_observasi_2')->nullable();
                $table->date('tgl_observasi_akhir')->nullable();
                $table->date('tgl_cetak')->nullable();
                $table->string('kota_cetak')->default('Pekanbaru');
                $table->text('catatan_umum')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lembar_observasi');
    }
};
