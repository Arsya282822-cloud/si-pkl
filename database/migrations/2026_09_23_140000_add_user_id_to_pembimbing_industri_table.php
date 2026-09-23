<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pastikan role instruktur ada
        if (Schema::hasTable('roles')) {
            Role::firstOrCreate(['nama_role' => 'instruktur']);
        }

        // 2. Tambahkan field user_id, jabatan, no_hp, email pada tabel pembimbing_industri
        Schema::table('pembimbing_industri', function (Blueprint $table) {
            if (!Schema::hasColumn('pembimbing_industri', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pembimbing_industri', 'jabatan')) {
                $table->string('jabatan', 100)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('pembimbing_industri', 'no_hp')) {
                $table->string('no_hp', 50)->nullable()->after('jabatan');
            }
            if (!Schema::hasColumn('pembimbing_industri', 'email')) {
                $table->string('email', 255)->nullable()->after('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembimbing_industri', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'jabatan', 'no_hp', 'email']);
        });
    }
};
