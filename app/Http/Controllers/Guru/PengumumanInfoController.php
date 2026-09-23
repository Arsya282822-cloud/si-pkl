<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanInfoController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->kategori;
        $q = $request->q;

        $pengumuman = Pengumuman::aktif()
            ->forRole('guru')
            ->when($kategori, function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('konten', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('guru.pengumuman.index', compact('pengumuman', 'kategori', 'q'));
    }
}
