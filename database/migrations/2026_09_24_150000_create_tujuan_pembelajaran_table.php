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
        Schema::create('tujuan_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete();
            $table->string('kode_jurusan', 20)->index();
            $table->string('konsentrasi_keahlian');
            $table->text('capaian_pembelajaran');
            $table->integer('nomor_urut')->default(1);
            $table->text('tujuan_pembelajaran');
            $table->string('tahun', 10)->default('2026');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_pembelajaran');
    }
};
