<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;
        $perusahaan = $pembimbing?->perusahaan;

        return view('instruktur.profil.index', compact('user', 'pembimbing', 'perusahaan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $pembimbing = $user->pembimbingIndustri;

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        if ($pembimbing) {
            $pembimbing->update([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'no_hp' => $request->no_hp,
            ]);
        }

        $user->name = $request->nama;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Profil instruktur berhasil diperbarui.');
    }
}
