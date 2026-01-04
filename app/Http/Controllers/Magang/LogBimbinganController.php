<?php

namespace App\Http\Controllers\Magang;

use App\Models\LogBimbingan;
use App\Models\DataMagang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogBimbinganController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'hr') {
            // HR: Lihat semua log bimbingan
            $log = LogBimbingan::with(['dataMagang.profilPeserta'])->orderBy('waktu_bimbingan', 'desc')->get();
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing: Lihat log bimbingan untuk peserta yang dibimbing
            $log = LogBimbingan::whereHas('dataMagang', function ($query) use ($user) {
                $query->where('pembimbing_id', $user->id);
            })->with(['dataMagang.profilPeserta'])->orderBy('waktu_bimbingan', 'desc')->get();
        } elseif ($user->role === 'magang') {
            // Magang: Lihat log bimbingan sendiri
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            $log = $dataMagang ? $dataMagang->logBimbingan()->orderBy('waktu_bimbingan', 'desc')->get() : collect();
        } else {
            $log = collect();
        }

        return view('magang.bimbingan.index', compact('log'));
    }

    public function create()
    {
        $user = auth()->user();

        // Get list peserta for dropdown (pembimbing & hr only)
        if ($user->role === 'pembimbing') {
            $dataMagang = DataMagang::where('pembimbing_id', $user->id)
                ->with('profilPeserta')
                ->get();
        } elseif ($user->role === 'hr') {
            $dataMagang = DataMagang::with('profilPeserta')->get();
        } else {
            // Magang tidak bisa create log bimbingan
            abort(403, 'Unauthorized');
        }

        return view('magang.bimbingan.create', compact('dataMagang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'waktu_bimbingan' => 'required|date',
            'catatan_peserta' => 'nullable|string',
            'catatan_pembimbing' => 'nullable|string',
        ]);

        // Verify user has access to this data_magang
        $user = auth()->user();
        $dataMagang = DataMagang::findOrFail($data['data_magang_id']);

        if ($user->role === 'pembimbing' && $dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized - Anda tidak membimbing peserta ini');
        }

        LogBimbingan::create($data);
        return redirect()->route('bimbingan.index')->with('success', 'Log bimbingan berhasil dibuat');
    }

    public function edit($id)
    {
        $log = LogBimbingan::with('dataMagang.profilPeserta')->findOrFail($id);
        $user = auth()->user();

        // Verify access
        if ($user->role === 'pembimbing' && $log->dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Get list peserta for dropdown
        if ($user->role === 'pembimbing') {
            $dataMagang = DataMagang::where('pembimbing_id', $user->id)
                ->with('profilPeserta')
                ->get();
        } elseif ($user->role === 'hr') {
            $dataMagang = DataMagang::with('profilPeserta')->get();
        }

        return view('magang.bimbingan.edit', compact('log', 'dataMagang'));
    }

    public function update(Request $request, $id)
    {
        $log = LogBimbingan::findOrFail($id);
        $user = auth()->user();

        // Verify access
        if ($user->role === 'pembimbing' && $log->dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'waktu_bimbingan' => 'required|date',
            'catatan_peserta' => 'nullable|string',
            'catatan_pembimbing' => 'nullable|string',
        ]);

        $log->update($data);
        return redirect()->route('bimbingan.index')->with('success', 'Log bimbingan berhasil diupdate');
    }

    public function destroy($id)
    {
        $log = LogBimbingan::findOrFail($id);
        $user = auth()->user();

        // Verify access
        if ($user->role === 'pembimbing' && $log->dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $log->delete();
        return redirect()->route('bimbingan.index')->with('success', 'Log bimbingan berhasil dihapus');
    }
}
