<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Guru;

return new class extends Migration
{
    public function up(): void
    {
        try {
            $roleGuru = Role::where('nama_role', 'guru')->first();
            $guruUserIds = Guru::pluck('user_id')->filter()->toArray();

            $query = User::query();
            if ($roleGuru) {
                $query->where('role_id', $roleGuru->id);
            }
            if (!empty($guruUserIds)) {
                $query->orWhereIn('id', $guruUserIds);
            }

            $query->update([
                'password' => Hash::make('guru1234'),
            ]);
        } catch (\Exception $e) {
            // Ignore if tables not yet available
        }
    }

    public function down(): void
    {
        // No reverse needed
    }
};
