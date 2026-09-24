<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pks')) {
            Schema::create('pks', function (Blueprint $table) {
                $table->id();
                $table->string('vld', 50)->nullable();
                $table->string('jenis_kerjasama')->nullable();
                $table->string('dunia_usaha_industri')->nullable();
                $table->string('nama_dudi');
                $table->string('nomor_pks')->nullable();
                $table->string('judul_pks')->nullable();
                $table->date('tgl_mulai')->nullable();
                $table->date('tgl_selesai')->nullable();
                $table->string('npwp_dudi')->nullable();
                $table->string('nama_bidang_usaha')->nullable();
                $table->string('telp_kantor', 50)->nullable();
                $table->string('fax', 50)->nullable();
                $table->string('contact_person')->nullable();
                $table->string('telepon_cp', 50)->nullable();
                $table->string('jabatan_cp')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pks');
    }
};
