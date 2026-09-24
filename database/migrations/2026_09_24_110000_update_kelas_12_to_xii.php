<?php

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kelas') && Schema::hasTable('jurusan')) {
            $kelas12List = Kelas::where('tingkat', '12')
                ->orWhere('nama_kelas', 'like', '12 %')
                ->orWhere('nama_kelas', 'like', '% 12 %')
                ->orWhere('nama_kelas', 'like', '% 12')
                ->get();

            foreach ($kelas12List as $kls) {
                $newNama = preg_replace('/\b12\b/', 'XII', trim($kls->nama_kelas));
                $duplicate = Kelas::where('id', '!=', $kls->id)
                    ->where('jurusan_id', $kls->jurusan_id)
                    ->where('nama_kelas', $newNama)
                    ->first();

                if ($duplicate) {
                    Siswa::where('kelas_id', $kls->id)->update(['kelas_id' => $duplicate->id]);
                    $duplicate->update(['tingkat' => 'XII']);
                    $kls->delete();
                } else {
                    $kls->update([
                        'nama_kelas' => $newNama,
                        'tingkat'    => 'XII',
                    ]);
                }
            }

            foreach (Jurusan::where('status', true)->get() as $jur) {
                $hasXii = Kelas::where('jurusan_id', $jur->id)
                    ->where('tingkat', 'XII')
                    ->exists();

                if (!$hasXii) {
                    Kelas::create([
                        'jurusan_id' => $jur->id,
                        'nama_kelas' => 'XII ' . $jur->kode_jurusan . ' 1',
                        'tingkat'    => 'XII',
                        'status'     => true,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // No reverse needed
    }
};
