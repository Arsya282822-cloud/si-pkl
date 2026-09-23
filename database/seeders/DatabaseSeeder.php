<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = \App\Models\Role::create(['nama_role' => 'admin']);
        \App\Models\Role::create(['nama_role' => 'guru']);
        \App\Models\Role::create(['nama_role' => 'siswa']);

        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => $roleAdmin->id,
            'status' => 'aktif',
        ]);
    }
}
