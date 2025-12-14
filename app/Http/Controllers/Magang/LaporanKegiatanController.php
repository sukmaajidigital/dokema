<?php

namespace App\Http\Controllers\Magang;

use App\Models\LaporanKegiatan;
use App\Models\DataMagang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanKegiatanController extends Controller
{
    public function index()
    {
        $laporan = LaporanKegiatan::all();
        return view('magang.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $dataMagangList = DataMagang::all();
        return view('magang.laporan.create', compact('dataMagangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'tanggal_laporan' => 'required|date',
            'deskripsi' => 'required',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);
        $data['status_verifikasi'] = 'menunggu';
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('laporan/lampiran', 'public');
        } else {
            $lampiranPath = null;
        }
        LaporanKegiatan::create([
            'data_magang_id' => $data['data_magang_id'],
            'tanggal_laporan' => $data['tanggal_laporan'],
            'deskripsi' => $data['deskripsi'],
            'path_lampiran' => $lampiranPath,
            'status_verifikasi' => $data['status_verifikasi'],
        ]);
        return redirect()->route('laporan.index', [$data['data_magang_id']])->with('success', 'Laporan berhasil dibuat');
    }

    public function edit($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $dataMagangList = DataMagang::all();
        return view('magang.laporan.edit', compact('laporan', 'dataMagangList'));
    }

    public function update(Request $request, $magangId, $id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'tanggal_laporan' => 'required|date',
            'deskripsi' => 'required',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('laporan/lampiran', 'public');
        } else {
            $lampiranPath = $laporan->path_lampiran;
        }
        $laporan->update([
            'data_magang_id' => $data['data_magang_id'],
            'tanggal_laporan' => $data['tanggal_laporan'],
            'deskripsi' => $data['deskripsi'],
            'path_lampiran' => $lampiranPath,
        ]);
        return redirect()->route('laporan.index', [$data['data_magang_id']])->with('success', 'Laporan berhasil diupdate');
    }

    public function destroy($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus');
    }

    public function approve($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        // Only pembimbing can approve
        if (Auth::user()->role !== 'pembimbing') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui laporan');
        }

        // Check if pembimbing is assigned to this intern
        if ($laporan->dataMagang->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda bukan pembimbing untuk magang ini');
        }

        $laporan->update([
            'status_verifikasi' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui');
    }

    public function reject(Request $request, $id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);

        // Only pembimbing can reject
        if (Auth::user()->role !== 'pembimbing') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak laporan');
        }

        // Check if pembimbing is assigned to this intern
        if ($laporan->dataMagang->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda bukan pembimbing untuk magang ini');
        }

        $request->validate([
            'catatan_verifikasi' => 'required|string|min:10',
        ]);

        $laporan->update([
            'status_verifikasi' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        return redirect()->back()->with('success', 'Laporan ditolak dengan catatan');
    }
}
