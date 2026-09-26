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

    public function test_attendance_with_selfie_photo_and_geofencing()
    {
        $roleSiswa = Role::firstOrCreate(['nama_role' => 'siswa']);

        $user = User::factory()->create([
            'role_id' => $roleSiswa->id,
            'status' => 'aktif',
        ]);

        $jurusan = Jurusan::firstOrCreate(
            ['kode_jurusan' => 'TKJ'],
            ['nama_jurusan' => 'Teknik Komputer dan Jaringan', 'status' => true]
        );

        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'XII TKJ 1'],
            ['jurusan_id' => $jurusan->id, 'tingkat' => 'XII', 'status' => true]
        );

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => $kelas->id,
            'jurusan_id' => $jurusan->id,
            'nis' => '54321',
            'nisn' => '0987654321',
            'nama' => 'SITI AMINAH',
            'jenis_kelamin' => 'P',
            'no_hp' => '081298765432',
        ]);

        $perusahaan = Perusahaan::create([
            'nama_perusahaan' => 'PT Garuda Cyber Indonesia',
            'alamat' => 'Jl. HR. Soebrantas',
            'kota' => 'Pekanbaru',
            'latitude' => '0.507068',
            'longitude' => '101.447779',
            'radius_meter' => 150,
            'status' => 'aktif',
        ]);

        $periode = PeriodePkl::create([
            'nama_periode' => 'PKL 2026',
            'tahun_ajaran' => '2026/2027',
            'tanggal_mulai' => now()->subMonths(1)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(3)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        $penempatan = Penempatan::create([
            'siswa_id' => $siswa->id,
            'perusahaan_id' => $perusahaan->id,
            'periode_pkl_id' => $periode->id,
        ]);

        // Base64 sample 1x1 transparent png
        $fakeBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        // 1. Submit Attendance Masuk with Selfie & Geolocation (inside radius)
        $responseMasuk = $this->actingAs($user)->post(route('siswa.absensi.store'), [
            'lokasi' => '0.507068,101.447779',
            'foto_masuk' => $fakeBase64,
        ]);

        $responseMasuk->assertRedirect(route('siswa.absensi.index'));
        $responseMasuk->assertSessionHas('success');

        $absenToday = AbsensiPkl::where('penempatan_id', $penempatan->id)->first();
        $this->assertNotNull($absenToday);
        $this->assertEquals('hadir', $absenToday->status);
        $this->assertEquals('dalam_radius', $absenToday->status_lokasi_masuk);
        $this->assertNotNull($absenToday->foto_masuk);
        $this->assertStringContainsString('absensi/masuk/', $absenToday->foto_masuk);

        // 2. Submit Attendance Pulang with Selfie
        $responseKeluar = $this->actingAs($user)->put(route('siswa.absensi.update', $absenToday->id), [
            'lokasi' => '0.507068,101.447779',
            'foto_keluar' => $fakeBase64,
        ]);

        $responseKeluar->assertRedirect(route('siswa.absensi.index'));
        $responseKeluar->assertSessionHas('success');

        $absenToday->refresh();
        $this->assertNotNull($absenToday->jam_keluar);
        $this->assertNotNull($absenToday->foto_keluar);
        $this->assertStringContainsString('absensi/pulang/', $absenToday->foto_keluar);
    }
}
