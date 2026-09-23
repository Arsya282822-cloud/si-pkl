<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (!\Illuminate\Support\Facades\Schema::hasTable('lembar_observasi')) {
            try {
                \Illuminate\Support\Facades\Schema::create('lembar_observasi', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('penempatan_id')->nullable();
                    $table->unsignedBigInteger('guru_id')->nullable();
                    $table->unsignedBigInteger('perusahaan_id')->nullable();
                    $table->unsignedBigInteger('siswa_id')->nullable();
                    
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
            } catch (\Exception $e) {
                // Ignore if already created concurrently
            }
        }
    }
}
