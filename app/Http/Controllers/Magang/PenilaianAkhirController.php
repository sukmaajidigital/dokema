<?php

namespace App\Http\Controllers\Magang;

use App\Models\PenilaianAkhir;
use App\Models\DataMagang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PenilaianAkhirController extends Controller
{
    public function index()
    {
        // Ambil semua penilaian dengan relasi ke data magang dan profil peserta
        $penilaianList = PenilaianAkhir::with(['dataMagang.profilPeserta', 'dataMagang.pembimbing'])
            ->latest()
            ->paginate(10);

        return view('magang.penilaian.index', compact('penilaianList'));
    }

    public function create($magangId)
    {
        $dataMagang = DataMagang::with('profilPeserta')->findOrFail($magangId);
        return view('magang.penilaian.create', compact('magangId', 'dataMagang'));
    }

    public function store(Request $request, $magangId)
    {
        $dataMagang = DataMagang::findOrFail($magangId);

        // Check if penilaian already exists
        if ($dataMagang->penilaianAkhir) {
            return redirect()->back()->with('error', 'Penilaian akhir sudah ada untuk magang ini');
        }

        // Only pembimbing can create penilaian
        if (Auth::user()->role !== 'pembimbing' || $dataMagang->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menilai magang ini');
        }

        $data = $request->validate([
            'nilai_kehadiran' => 'required|numeric|min:0|max:100',
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan' => 'required|numeric|min:0|max:100',
            'nilai_sikap' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'required|string|min:20',
            'surat_nilai' => 'nullable|file|mimes:pdf',
        ]);

        // Calculate average score
        $nilaiRataRata = ($data['nilai_kehadiran'] + $data['nilai_kedisiplinan'] +
            $data['nilai_keterampilan'] + $data['nilai_sikap']) / 4;

        $suratNilaiPath = null;
        if ($request->hasFile('surat_nilai')) {
            $suratNilaiPath = $request->file('surat_nilai')->store('surat_nilai', 'public');
        }

        PenilaianAkhir::create([
            'data_magang_id' => $magangId,
            'nilai_kehadiran' => $data['nilai_kehadiran'],
            'nilai_kedisiplinan' => $data['nilai_kedisiplinan'],
            'nilai_keterampilan' => $data['nilai_keterampilan'],
            'nilai_sikap' => $data['nilai_sikap'],
            'nilai_rata_rata' => $nilaiRataRata,
            'umpan_balik' => $data['umpan_balik'],
            'path_surat_nilai' => $suratNilaiPath,
            'tanggal_penilaian' => now(),
        ]);

        // Update workflow status to evaluated
        $dataMagang->update(['workflow_status' => 'evaluated']);

        return redirect()->route('penilaian.index')->with('success', 'Penilaian akhir berhasil dibuat');
    }

    public function edit($magangId, $id)
    {
        $penilaian = PenilaianAkhir::with('dataMagang.profilPeserta')->findOrFail($id);
        return view('magang.penilaian.edit', compact('penilaian', 'magangId'));
    }

    public function update(Request $request, $magangId, $id)
    {
        $penilaian = PenilaianAkhir::findOrFail($id);
        $data = $request->validate([
            'nilai' => 'required|numeric|min:0|max:4',
            'umpan_balik' => 'nullable|string',
            'path_surat_nilai' => 'nullable|string',
        ]);
        $penilaian->update($data);
        return redirect()->route('penilaian.index')->with('success', 'Penilaian akhir berhasil diupdate');
    }

    public function destroy($magangId, $id)
    {
        $penilaian = PenilaianAkhir::findOrFail($id);
        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian akhir berhasil dihapus');
    }
}
