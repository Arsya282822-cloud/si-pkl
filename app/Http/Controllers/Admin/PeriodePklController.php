<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\PeriodePkl;
use Illuminate\Http\Request;

class PeriodePklController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $statusFilter = trim((string) $request->input('status'));

        $query = PeriodePkl::query()
            ->withCount('penempatan')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama_periode', 'like', "%{$q}%")
                        ->orWhere('tahun_ajaran', 'like', "%{$q}%");
                });
            })
            ->when($statusFilter !== '', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderByRaw("CASE WHEN status = 'aktif' THEN 1 WHEN status = 'nonaktif' THEN 2 ELSE 3 END")
            ->orderBy('tanggal_mulai', 'desc');

        $periode = $query->paginate(9)->withQueryString();

        // Statistics
        $totalPeriode = PeriodePkl::count();
        $totalAktif = PeriodePkl::where('status', 'aktif')->count();
        $totalSelesai = PeriodePkl::where('status', 'selesai')->count();
        $totalSiswaPkl = Penempatan::count();
        $activePeriod = PeriodePkl::where('status', 'aktif')->withCount('penempatan')->first();

        return view('admin.periode.index', compact(
            'periode',
            'q',
            'statusFilter',
            'totalPeriode',
            'totalAktif',
            'totalSelesai',
            'totalSiswaPkl',
            'activePeriod'
        ));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif,selesai'],
        ]);

        PeriodePkl::create($validated);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode PKL berhasil ditambahkan.');
    }

    public function edit(PeriodePkl $periode)
    {
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, PeriodePkl $periode)
    {
        $validated = $request->validate([
            'nama_periode' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:aktif,nonaktif,selesai'],
        ]);

        $periode->update($validated);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode PKL berhasil diperbarui.');
    }

    public function destroy(PeriodePkl $periode)
    {
        if ($periode->penempatan()->exists()) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah memiliki penempatan PKL.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode PKL berhasil dihapus.');
    }
}
