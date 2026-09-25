<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'all');
        $search = $request->get('search');

        $query = Pengumuman::with('author')->latest();

        if ($kategori !== 'all' && in_array($kategori, ['info', 'penting', 'jadwal', 'peringatan'])) {
            $query->where('kategori', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $pengumuman = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => Pengumuman::count(),
            'aktif' => Pengumuman::where('status', 'aktif')->count(),
            'pinned' => Pengumuman::where('is_pinned', true)->count(),
        ];

        return view('admin.pengumuman.index', compact('pengumuman', 'stats', 'kategori', 'search'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:info,penting,jadwal,peringatan',
            'target_role' => 'required|in:semua,siswa,guru',
            'is_pinned' => 'nullable|boolean',
            'status' => 'required|in:aktif,nonaktif',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = 'lampiran_'.time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/pengumuman'), $fileName);
            $filePath = 'uploads/pengumuman/'.$fileName;
        }

        Pengumuman::create([
            'author_id' => Auth::id(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'target_role' => $request->target_role,
            'is_pinned' => $request->has('is_pinned'),
            'file_lampiran' => $filePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:info,penting,jadwal,peringatan',
            'target_role' => 'required|in:semua,siswa,guru',
            'is_pinned' => 'nullable|boolean',
            'status' => 'required|in:aktif,nonaktif',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:5120',
        ]);

        $filePath = $pengumuman->file_lampiran;
        if ($request->hasFile('file_lampiran')) {
            if ($filePath && file_exists(public_path($filePath))) {
                @unlink(public_path($filePath));
            }
            $file = $request->file('file_lampiran');
            $fileName = 'lampiran_'.time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/pengumuman'), $fileName);
            $filePath = 'uploads/pengumuman/'.$fileName;
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'target_role' => $request->target_role,
            'is_pinned' => $request->has('is_pinned'),
            'file_lampiran' => $filePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_lampiran && file_exists(public_path($pengumuman->file_lampiran))) {
            @unlink(public_path($pengumuman->file_lampiran));
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman telah dihapus.');
    }
}
