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
        // Filter penilaian berdasarkan role pengguna (Issue #5)
        if (Auth::user()->role === 'magang') {
            // Peserta hanya bisa lihat penilaian milik sendiri
            $profilPeserta = Auth::user()->profilPeserta;
            if (!$profilPeserta) {
                return view('magang.penilaian.index', ['penilaianList' => []]);
            }

            // dataMagang is hasMany, so get first record
            $dataMagang = $profilPeserta->dataMagang()->first();
            if (!$dataMagang || !$dataMagang->penilaianAkhir) {
                return view('magang.penilaian.index', ['penilaianList' => []]);
            }
            $penilaianList = collect([$dataMagang->penilaianAkhir]);
        } elseif (Auth::user()->role === 'pembimbing') {
            // Pembimbing hanya bisa lihat penilaian peserta yang dibimbing
            $penilaianList = PenilaianAkhir::whereIn(
                'data_magang_id',
                Auth::user()->magangDibimbing->pluck('id')
            )
                ->with(['dataMagang.profilPeserta', 'dataMagang.pembimbing'])
                ->latest()
                ->paginate(10);
        } else {
            // HR bisa lihat semua penilaian
            $penilaianList = PenilaianAkhir::with(['dataMagang.profilPeserta', 'dataMagang.pembimbing'])
                ->latest()
                ->paginate(10);
        }

        return view('magang.penilaian.index', compact('penilaianList'));
    }

    public function create()
    {
        $user = Auth::user();

        // Only pembimbing & hr can create penilaian
        if (!in_array($user->role, ['pembimbing', 'hr'])) {
            abort(403, 'Unauthorized');
        }

        // Get list peserta for dropdown
        if ($user->role === 'pembimbing') {
            $dataMagang = DataMagang::where('pembimbing_id', $user->id)
                ->whereDoesntHave('penilaianAkhir') // Only peserta without penilaian
                ->with('profilPeserta')
                ->get();
        } elseif ($user->role === 'hr') {
            $dataMagang = DataMagang::whereDoesntHave('penilaianAkhir')
                ->with('profilPeserta')
                ->get();
        }

        return view('magang.penilaian.create', compact('dataMagang'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'nilai_kehadiran' => 'required|numeric|min:0|max:100',
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_keterampilan' => 'required|numeric|min:0|max:100',
            'nilai_sikap' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'required|string|min:20',
            'surat_nilai' => 'nullable|file|mimes:pdf',
        ]);

        $dataMagang = DataMagang::findOrFail($data['data_magang_id']);

        // Check if penilaian already exists
        if ($dataMagang->penilaianAkhir) {
            return redirect()->back()->with('error', 'Penilaian akhir sudah ada untuk peserta ini');
        }

        // Verify access
        if ($user->role === 'pembimbing' && $dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized - Anda tidak membimbing peserta ini');
        }

        // Calculate average score
        $nilaiRataRata = ($data['nilai_kehadiran'] + $data['nilai_kedisiplinan'] +
            $data['nilai_keterampilan'] + $data['nilai_sikap']) / 4;

        $suratNilaiPath = null;
        if ($request->hasFile('surat_nilai')) {
            $suratNilaiPath = $request->file('surat_nilai')->store('surat_nilai', 'public');
        }

        PenilaianAkhir::create([
            'data_magang_id' => $data['data_magang_id'],
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

    public function show($id)
    {
        $penilaian = PenilaianAkhir::with('dataMagang.profilPeserta')->findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang only see their own
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang || $penilaian->data_magang_id !== $dataMagang->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only see peserta they supervise
            if ($penilaian->dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('magang.penilaian.show', compact('penilaian'));
    }

    public function edit($id)
    {
        $penilaian = PenilaianAkhir::with('dataMagang.profilPeserta')->findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'pembimbing' && $penilaian->dataMagang->pembimbing_id !== $user->id) {
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

        return view('magang.penilaian.edit', compact('penilaian', 'dataMagang'));
    }

    public function update(Request $request, $id)
    {
        $penilaian = PenilaianAkhir::findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'pembimbing' && $penilaian->dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
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

        $updateData = [
            'data_magang_id' => $data['data_magang_id'],
            'nilai_kehadiran' => $data['nilai_kehadiran'],
            'nilai_kedisiplinan' => $data['nilai_kedisiplinan'],
            'nilai_keterampilan' => $data['nilai_keterampilan'],
            'nilai_sikap' => $data['nilai_sikap'],
            'nilai_rata_rata' => $nilaiRataRata,
            'umpan_balik' => $data['umpan_balik'],
        ];

        if ($request->hasFile('surat_nilai')) {
            $updateData['path_surat_nilai'] = $request->file('surat_nilai')->store('surat_nilai', 'public');
        }

        $penilaian->update($updateData);
        return redirect()->route('penilaian.index')->with('success', 'Penilaian akhir berhasil diupdate');
    }

    public function destroy($id)
    {
        $penilaian = PenilaianAkhir::findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'pembimbing' && $penilaian->dataMagang->pembimbing_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian akhir berhasil dihapus');
    }
}
