<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat aktivitas ke database.
     *
     * @param string $modul
     * @param string $aktivitas
     * @param string|null $deskripsi
     * @param \App\Models\User|null $user
     * @return \App\Models\ActivityLog|null
     */
    public static function log(string $modul, string $aktivitas, ?string $deskripsi = null, $user = null)
    {
        try {
            $currentUser = $user ?? Auth::user();
            
            return ActivityLog::create([
                'user_id'    => $currentUser?->id,
                'user_name'  => $currentUser?->name ?? 'Tamu / Sistem',
                'role'       => $currentUser?->role?->nama_role ?? 'guest',
                'modul'      => $modul,
                'aktivitas'  => $aktivitas,
                'deskripsi'  => $deskripsi,
                'ip_address' => Request::ip(),
                'user_agent' => substr(Request::userAgent() ?? '', 0, 255),
            ]);
        } catch (\Exception $e) {
            // Silently fail agar tidak menghentikan flow utama jika tabel belum dimigrate
            return null;
        }
    }
}
