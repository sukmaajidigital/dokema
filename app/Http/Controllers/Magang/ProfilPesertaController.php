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
        // Filter profil berdasarkan role (Issue #5)
        if (Auth::user()->role === 'magang') {
            // Peserta hanya bisa lihat profil sendiri
            $profils = Auth::user()->profilPeserta ? collect([Auth::user()->profilPeserta]) : collect([]);
        } elseif (Auth::user()->role === 'pembimbing') {
            // Pembimbing hanya bisa lihat profil peserta yang dibimbing
            $profils = ProfilPeserta::whereIn(
                'id',
                Auth::user()->magangDibimbing->pluck('profil_peserta_id')
            )->with('user')->get();
        } else {
            // HR bisa lihat semua profil
            $profils = ProfilPeserta::with('user')->get();
        }
        return view('magang.profil.index', compact('profils'));
    }

    public function create()
    {
        $user = Auth::user();

        // Only HR can create new profiles
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        // Get users without profiles
        $users = \App\Models\User::where('role', 'magang')
            ->whereDoesntHave('profilPeserta')
            ->get();
        return view('magang.profil.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Only HR can create new profiles
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:profil_peserta,user_id',
            'nama_peserta' => 'required|string|max:255',
            'nim' => 'required|string|unique:profil_peserta,nim',
            'universitas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        ProfilPeserta::create($data);
        return redirect()->route('profil.index')->with('success', 'Profil berhasil dibuat');
    }

    public function edit($id)
    {
        $profil = ProfilPeserta::with('user')->findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang only edit their own profile
            if (!$user->profilPeserta || $user->profilPeserta->id !== $profil->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only edit peserta they supervise
            $dataMagang = $profil->dataMagang()->first();
            if (!$dataMagang || $dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        $users = \App\Models\User::where('role', 'magang')
            ->whereDoesntHave('profilPeserta')
            ->orWhere('id', $profil->user_id)
            ->get();
        return view('magang.profil.edit', compact('profil', 'users'));
    }

    public function update(Request $request, $id)
    {
        $profil = ProfilPeserta::findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang only update their own profile
            if (!$user->profilPeserta || $user->profilPeserta->id !== $profil->id) {
                abort(403, 'Unauthorized');
            }
            // Magang cannot change user_id
            $data = $request->validate([
                'nama_peserta' => 'required|string|max:255',
                'nim' => 'required|string|unique:profil_peserta,nim,' . $profil->id,
                'universitas' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'alamat' => 'nullable|string',
            ]);
            $data['user_id'] = $profil->user_id; // Keep original
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only update peserta they supervise
            $dataMagang = $profil->dataMagang()->first();
            if (!$dataMagang || $dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            // Pembimbing can update but not user_id
            $data = $request->validate([
                'nama_peserta' => 'required|string|max:255',
                'nim' => 'required|string|unique:profil_peserta,nim,' . $profil->id,
                'universitas' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'alamat' => 'nullable|string',
            ]);
            $data['user_id'] = $profil->user_id; // Keep original
        } else {
            // HR can change everything including user_id
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'nama_peserta' => 'required|string|max:255',
                'nim' => 'required|string|unique:profil_peserta,nim,' . $profil->id,
                'universitas' => 'required|string|max:255',
                'jurusan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'alamat' => 'nullable|string',
            ]);
        }

        $profil->update($data);
        return redirect()->route('profil.index')->with('success', 'Profil berhasil diupdate');
    }

    public function show($id)
    {
        $profil = ProfilPeserta::with(['user', 'dataMagang'])->findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang only view their own profile
            if (!$user->profilPeserta || $user->profilPeserta->id !== $profil->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only view peserta they supervise
            $dataMagang = $profil->dataMagang()->first();
            if (!$dataMagang || $dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('magang.profil.show', compact('profil'));
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Only HR can delete profiles
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        $profil = ProfilPeserta::findOrFail($id);
        $profil->delete();
        return redirect()->route('profil.index')->with('success', 'Profil berhasil dihapus');
    }
}
