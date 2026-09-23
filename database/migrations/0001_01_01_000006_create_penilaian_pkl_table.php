<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penempatan_id')->constrained('penempatan')->cascadeOnDelete()->unique();
            $table->integer('nilai_sikap')->nullable();
            $table->integer('nilai_keterampilan')->nullable();
            $table->integer('nilai_pengetahuan')->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_pkl');
    }
};
