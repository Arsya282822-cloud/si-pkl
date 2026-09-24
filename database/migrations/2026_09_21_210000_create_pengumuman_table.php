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
        if (!Schema::hasTable('pengumuman')) {
            Schema::create('pengumuman', function (Blueprint $table) {
                $table->id();
                $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
                $table->string('judul', 255);
                $table->text('konten');
                $table->enum('kategori', ['info', 'penting', 'jadwal', 'peringatan'])->default('info');
                $table->enum('target_role', ['semua', 'siswa', 'guru'])->default('semua');
                $table->boolean('is_pinned')->default(false);
                $table->string('file_lampiran')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
