<?php

namespace App\Services;

use App\Models\PembimbingIndustri;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InstrukturAccountService
{
    /**
     * Generate / Sync concise user accounts for all DUDI companies
     * Format: dudi1@dudi.com, dudi2@dudi.com, ... (Password: dudi1234)
     *
     * @return int Count of accounts synced/created
     */
    public static function syncAllDudiAccounts(string $defaultPassword = 'dudi1234'): int
    {
        $roleInstruktur = Role::firstOrCreate(['nama_role' => 'instruktur']);
        $companies = Perusahaan::all();
        $count = 0;

        foreach ($companies as $company) {
            // Short, concise email: dudi1@dudi.com, dudi2@dudi.com, etc.
            $shortEmail = 'dudi'.$company->id.'@dudi.com';
            $pembimbing = PembimbingIndustri::where('perusahaan_id', $company->id)->first();

            // Find existing user by short email or linked user_id
            $userByEmail = User::where('email', $shortEmail)->first();
            $userById = ($pembimbing && $pembimbing->user_id) ? User::find($pembimbing->user_id) : null;

            if ($userByEmail && $userById && $userByEmail->id !== $userById->id) {
                // Delete the stale/old user record
                $userById->delete();
                $user = $userByEmail;
            } else {
                $user = $userByEmail ?: $userById;
            }

            if ($user) {
                $user->name = 'Instruktur '.($company->nama_perusahaan ?: 'DUDI '.$company->id);
                $user->email = $shortEmail;
                $user->role_id = $roleInstruktur->id;
                $user->status = 'aktif';
                $user->password = Hash::make($defaultPassword);
                $user->save();
            } else {
                $user = User::create([
                    'name' => 'Instruktur '.($company->nama_perusahaan ?: 'DUDI '.$company->id),
                    'email' => $shortEmail,
                    'password' => Hash::make($defaultPassword),
                    'role_id' => $roleInstruktur->id,
                    'status' => 'aktif',
                ]);
            }

            if ($pembimbing) {
                $pembimbing->update([
                    'user_id' => $user->id,
                    'email' => $shortEmail,
                    'nama' => $pembimbing->nama ?: ($company->nama_pimpinan ?: 'Pembimbing '.$company->nama_perusahaan),
                    'jabatan' => $pembimbing->jabatan ?: 'Pembimbing Lapangan DUDI',
                    'no_hp' => $pembimbing->no_hp ?: ($company->no_telepon ?: '081200000000'),
                ]);
            } else {
                PembimbingIndustri::create([
                    'perusahaan_id' => $company->id,
                    'user_id' => $user->id,
                    'nama' => $company->nama_pimpinan ?: ('Pembimbing '.$company->nama_perusahaan),
                    'jabatan' => 'Pembimbing Lapangan DUDI',
                    'no_hp' => $company->no_telepon ?: '081200000000',
                    'email' => $shortEmail,
                ]);
            }

            $count++;
        }

        // Clean up legacy unused users
        User::where('role_id', $roleInstruktur->id)
            ->where('email', 'like', 'instruktur.%@dudi.com')
            ->whereNotIn('id', PembimbingIndustri::pluck('user_id')->filter()->toArray())
            ->delete();

        return $count;
    }
}
