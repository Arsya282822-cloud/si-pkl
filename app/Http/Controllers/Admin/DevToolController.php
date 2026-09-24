<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\Guru;
use App\Models\JurnalPkl;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Monitoring;
use App\Models\Penempatan;
use App\Models\PenilaianPkl;
use App\Models\PeriodePkl;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DevToolController extends Controller
{
    /**
     * Redirect user to role-specific dashboard.
     */
    public function dashboardRedirect()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('login');
        }

        $role = $user->role?->nama_role ?? '';

        return match ($role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'siswa'      => redirect()->route('siswa.dashboard'),
            'guru'       => redirect()->route('guru.dashboard'),
            'instruktur' => redirect()->route('instruktur.dashboard'),
            default      => view('dashboard'),
        };
    }

    /**
     * Setup dummy data for testing/simulation (protected for admin & non-production).
     */
    public function setupDummy()
    {
        if (app()->environment('production') && !config('app.debug')) {
            return redirect()->route('admin.dashboard')->with('error', 'Fitur Setup Dummy dinonaktifkan pada mode Production.');
        }

        $roleGuru = Role::firstOrCreate(['nama_role' => 'guru']);
        $roleSiswa = Role::firstOrCreate(['nama_role' => 'siswa']);

        $jurusan = Jurusan::firstOrCreate(
            ['kode_jurusan' => 'RPL'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'status' => true]
        );
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'XII RPL 1'],
            ['jurusan_id' => $jurusan->id, 'tingkat' => 'XII', 'wali_kelas' => 'Bapak Wali', 'status' => true]
        );

        $perusahaanList = [];
        for ($i = 1; $i <= 3; $i++) {
            $perusahaanList[] = Perusahaan::firstOrCreate(
                ['nama_perusahaan' => 'PT Teknologi Masa Depan ' . $i],
                ['bidang_usaha' => 'IT Software House', 'alamat' => 'Jl. Sudirman No. 12' . $i, 'pembimbing_industri' => 'Bpk. Budi ' . $i]
            );
        }

        $periode = PeriodePkl::firstOrCreate(
            ['nama_periode' => 'Gelombang 1 Tahun 2026'],
            [
                'tahun_ajaran' => '2026/2027',
                'tanggal_mulai' => now()->subDays(15)->format('Y-m-d'),
                'tanggal_selesai' => now()->addMonths(3)->format('Y-m-d'),
                'status' => 'aktif',
            ]
        );

        $gurus = [];
        for ($i = 1; $i <= 5; $i++) {
            $userGuru = User::firstOrCreate(
                ['email' => "guru{$i}@guru.com"],
                ['name' => "Bapak Guru {$i}", 'password' => bcrypt('guru1234'), 'role_id' => $roleGuru->id, 'status' => 'aktif']
            );
            $gurus[] = Guru::firstOrCreate(
                ['nip' => "1000000{$i}"],
                ['user_id' => $userGuru->id, 'nama' => "Bapak Guru {$i}", 'no_hp' => "0812000000{$i}"]
            );
        }

        for ($i = 1; $i <= 20; $i++) {
            $userSiswa = User::firstOrCreate(
                ['email' => "siswa{$i}@siswa.com"],
                ['name' => "Siswa Teladan {$i}", 'password' => bcrypt('password'), 'role_id' => $roleSiswa->id, 'status' => 'aktif']
            );

            $nis = str_pad($i, 4, '0', STR_PAD_LEFT);
            $siswa = Siswa::firstOrCreate(
                ['nis' => "2026{$nis}"],
                ['user_id' => $userSiswa->id, 'kelas_id' => $kelas->id, 'jurusan_id' => $jurusan->id, 'nama' => "Siswa Teladan {$i}"]
            );

            $guru_id = $gurus[$i % 5]->id;
            $perusahaan_id = $perusahaanList[$i % 3]->id;

            $penempatan = Penempatan::firstOrCreate([
                'siswa_id' => $siswa->id,
                'periode_pkl_id' => $periode->id,
            ], [
                'guru_id' => $guru_id,
                'perusahaan_id' => $perusahaan_id,
            ]);

            $hari_terakhir = rand(3, 6);
            for ($j = $hari_terakhir; $j >= 0; $j--) {
                $tanggal = now()->subDays($j)->format('Y-m-d');

                AbsensiPkl::firstOrCreate(
                    ['penempatan_id' => $penempatan->id, 'tanggal' => $tanggal],
                    ['status' => 'hadir', 'jam_masuk' => '08:00', 'jam_keluar' => '16:00', 'keterangan' => '-']
                );

                JurnalPkl::firstOrCreate(
                    ['penempatan_id' => $penempatan->id, 'tanggal' => $tanggal],
                    ['kegiatan' => "Melakukan tugas harian ke-" . (10 - $j) . " di industri.", 'status_validasi' => $j > 2 ? 'disetujui' : 'menunggu']
                );
            }

            if ($i % 3 == 0) {
                PenilaianPkl::firstOrCreate(
                    ['penempatan_id' => $penempatan->id],
                    [
                        'nilai_sikap' => rand(80, 95),
                        'nilai_keterampilan' => rand(80, 95),
                        'nilai_pengetahuan' => rand(80, 95),
                        'nilai_akhir' => rand(80, 95),
                        'catatan_guru' => 'Siswa berkinerja baik.',
                    ]
                );
            }
        }

        foreach ($gurus as $index => $g) {
            Monitoring::firstOrCreate(
                [
                    'guru_id' => $g->id,
                    'perusahaan_id' => $perusahaanList[$index % 3]->id,
                    'tanggal_kunjungan' => now()->subDays(rand(1, 5))->format('Y-m-d'),
                ],
                ['catatan' => 'Kunjungan monitoring berjalan lancar, siswa dalam keadaan sehat dan aktif.']
            );
        }

        ActivityLogger::log('DevTools', 'Setup Dummy Data', 'Menjalankan generator data simulasi PKL');

        return redirect()->route('admin.dashboard')->with('success', 'Data Dummy PKL berhasil di-generate!');
    }

    /**
     * Wipe only dummy simulation data safely.
     */
    public function wipeDummy()
    {
        if (app()->environment('production') && !config('app.debug')) {
            return redirect()->route('admin.dashboard')->with('error', 'Fitur Wipe Dummy dinonaktifkan pada mode Production.');
        }

        Schema::disableForeignKeyConstraints();
        try {
            $dummyUsers = User::where('email', 'like', '%@guru.com')
                ->orWhere('email', 'like', '%@siswa.com')
                ->get();

            $userIds = $dummyUsers->pluck('id')->toArray();

            if (count($userIds) > 0) {
                $siswaIds = Siswa::whereIn('user_id', $userIds)->pluck('id')->toArray();
                $guruIds = Guru::whereIn('user_id', $userIds)->pluck('id')->toArray();

                $penempatanIds = Penempatan::whereIn('siswa_id', $siswaIds)
                    ->orWhereIn('guru_id', $guruIds)
                    ->pluck('id')
                    ->toArray();

                Monitoring::whereIn('guru_id', $guruIds)->delete();
                PenilaianPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                AbsensiPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                JurnalPkl::whereIn('penempatan_id', $penempatanIds)->delete();
                Penempatan::whereIn('id', $penempatanIds)->delete();

                Siswa::whereIn('id', $siswaIds)->delete();
                Guru::whereIn('id', $guruIds)->delete();
                User::whereIn('id', $userIds)->delete();
            }

            Schema::enableForeignKeyConstraints();
            ActivityLogger::log('DevTools', 'Wipe Dummy Data', 'Membersihkan data akun simulasi dummy');

            return redirect()->route('admin.dashboard')->with('success', 'Akun Dummy berhasil dibersihkan.');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            return redirect()->route('admin.dashboard')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
