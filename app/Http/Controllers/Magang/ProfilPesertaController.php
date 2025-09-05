<?php

namespace App\Http\Controllers\Magang;

use App\Models\ProfilPeserta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfilPesertaController extends Controller
{
    public function index()
    {
        $profils = ProfilPeserta::with('user')->get();
        return view('magang.profil.index', compact('profils'));
    }

    public function create()
    {
        $users = \App\Models\User::where('role', 'magang')->get();
        return view('magang.profil.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nim' => 'required|unique:profil_peserta',
            'universitas' => 'required',
            'jurusan' => 'required',
            'no_telepon' => 'required',
            'alamat' => 'nullable',
        ]);
        ProfilPeserta::create($data);
        return redirect()->route('profil.index')->with('success', 'Profil berhasil dibuat');
    }

    public function edit($id)
    {
        $profil = ProfilPeserta::findOrFail($id);
        $users = \App\Models\User::where('role', 'magang')->get();
        return view('magang.profil.edit', compact('profil', 'users'));
    }

    public function update(Request $request, $id)
    {
        $profil = ProfilPeserta::findOrFail($id);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nim' => 'required|unique:profil_peserta,nim,' . $profil->id,
            'universitas' => 'required',
            'jurusan' => 'required',
            'no_telepon' => 'required',
            'alamat' => 'nullable',
        ]);
        $profil->update($data);
        return redirect()->route('profil.index')->with('success', 'Profil berhasil diupdate');
    }

    // ...existing code...
    public function destroy($id)
    {
        $profil = ProfilPeserta::findOrFail($id);
        $profil->delete();
        return redirect()->route('profil.index')->with('success', 'Profil berhasil dihapus');
    }
}
