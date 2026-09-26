<?php

namespace Tests\Feature\Siswa;

use App\Models\AbsensiPkl;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Penempatan;
use App\Models\PeriodePkl;
use App\Models\Perusahaan;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceJournalLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_is_locked_when_past_journal_is_unfilled()
    {
        $roleSiswa = Role::firstOrCreate(['nama_role' => 'siswa']);

        $user = User::factory()->create([
            'role_id' => $roleSiswa->id,
            'status' => 'aktif',
        ]);

        $jurusan = Jurusan::firstOrCreate(
            ['kode_jurusan' => 'RPL'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'status' => true]
        );

        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'XII RPL 1'],
            ['jurusan_id' => $jurusan->id, 'tingkat' => 'XII', 'status' => true]
        );

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'jurusan_id' => $jurusan->id,
            'nis' => '12345',
            'nisn' => '1234567890',
            'nama' => 'BUDI SANTOSO',
            'jenis_kelamin' => 'L',
            'no_hp' => '08123456789',
        ]);

        $perusahaan = Perusahaan::create([
            'nama_perusahaan' => 'PT Test Teknologi',
            'alamat' => 'Jl. Test No. 123',
            'kota' => 'Pekanbaru',
            'status' => 'aktif',
        ]);

        $periode = PeriodePkl::create([
            'nama_periode' => 'PKL 2026',
            'tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => now()->subMonths(2)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        $penempatan = Penempatan::create([
            'siswa_id' => $siswa->id,
            'perusahaan_id' => $perusahaan->id,
            'periode_pkl_id' => $periode->id,
        ]);

        // Yesterday attendance without journal
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        AbsensiPkl::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => $yesterday,
            'status' => 'hadir',
            'jam_masuk' => '07:30:00',
        ]);

        // 1. Try to access attendance page today -> Should be locked
        $response = $this->actingAs($user)->get(route('siswa.absensi.index'));
        $response->assertStatus(200);
        $response->assertSee('Presensi Hari Ini Terkunci!');

        // 2. Try to check in today -> Should be blocked and redirected with error
        $postResponse = $this->actingAs($user)->post(route('siswa.absensi.store'), [
            'latitude' => 0.5,
            'longitude' => 101.4,
        ]);
        $postResponse->assertRedirect(route('siswa.absensi.index'));
        $postResponse->assertSessionHas('error');

        // 3. Now submit missing journal for yesterday
        $journalResponse = $this->actingAs($user)->post(route('siswa.jurnal.store'), [
            'tanggal' => $yesterday,
            'kegiatan' => 'Membuat dokumentasi sistem dan pengujian fitur.',
        ]);
        $journalResponse->assertRedirect(route('siswa.jurnal.index'));
        $this->assertDatabaseHas('jurnal_pkl', [
            'penempatan_id' => $penempatan->id,
            'tanggal' => $yesterday,
        ]);

        // 4. Now check attendance page again -> lock must be gone
        $responseAfter = $this->actingAs($user)->get(route('siswa.absensi.index'));
        $responseAfter->assertStatus(200);
        $responseAfter->assertDontSee('Presensi Hari Ini Terkunci!');
    }
}
