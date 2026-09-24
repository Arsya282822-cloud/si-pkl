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
        if (!Schema::hasTable('pengajuan_pkl')) {
            Schema::create('pengajuan_pkl', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('periode_pkl_id')->constrained('periode_pkl')->cascadeOnDelete();
                $table->string('nama_perusahaan', 255);
                $table->string('bidang_usaha', 255)->nullable();
                $table->text('alamat_perusahaan');
                $table->string('kota', 100)->nullable();
                $table->string('nama_pimpinan', 255)->nullable();
                $table->string('kontak_person', 255)->nullable();
                $table->string('no_telepon', 50)->nullable();
                $table->string('email', 255)->nullable();
                $table->text('alasan_memilih')->nullable();
                $table->string('file_surat_balasan')->nullable(); // Dokumen balasan / acceptance letter jika ada
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->text('catatan_verifikasi')->nullable();
                $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('diverifikasi_pada')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pkl');
    }
};
