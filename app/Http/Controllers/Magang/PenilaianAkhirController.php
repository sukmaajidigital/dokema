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
        $user = Auth::user();

        // Get list peserta magang berdasarkan role
        if ($user->role === 'pembimbing') {
            // Pembimbing hanya lihat peserta yang dibimbing
            $dataMagangList = DataMagang::where('pembimbing_id', $user->id)
                ->with(['profilPeserta', 'penilaianAkhir'])
                ->latest()
                ->get();
        } elseif ($user->role === 'hr') {
            // HR lihat semua peserta
            $dataMagangList = DataMagang::with(['profilPeserta', 'pembimbing', 'penilaianAkhir'])
                ->latest()
                ->get();
        } else {
            // Magang hanya lihat data sendiri
            $profilPeserta = $user->profilPeserta;
            if (!$profilPeserta) {
                $dataMagangList = collect();
            } else {
                $dataMagangList = $profilPeserta->dataMagang()->with('penilaianAkhir')->get();
            }
        }

        return view('magang.penilaian.index', compact('dataMagangList'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // Only pembimbing & hr can create penilaian
        if (!in_array($user->role, ['pembimbing', 'hr'])) {
            abort(403, 'Unauthorized');
        }

        // Get selected data_magang_id from query parameter
        $selectedMagangId = $request->query('data_magang_id');

        // Get list peserta for dropdown (hanya yang belum dinilai)
        if ($user->role === 'pembimbing') {
            $dataMagangList = DataMagang::where('pembimbing_id', $user->id)
                ->whereDoesntHave('penilaianAkhir')
                ->with('profilPeserta')
                ->get();
        } else {
            $dataMagangList = DataMagang::whereDoesntHave('penilaianAkhir')
                ->with('profilPeserta')
                ->get();
        }

        // If selected ID provided, verify it's in the list
        $selectedMagang = null;
        if ($selectedMagangId) {
            $selectedMagang = $dataMagangList->firstWhere('id', $selectedMagangId);
        }

        return view('magang.penilaian.create', compact('dataMagangList', 'selectedMagang'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'data_magang_id' => 'required|exists:data_magang,id',
            'nilai_keputusan_pemberi' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_prioritas' => 'required|numeric|min:0|max:100',
            'nilai_tepat_waktu' => 'required|numeric|min:0|max:100',
            'nilai_bekerja_sama' => 'required|numeric|min:0|max:100',
            'nilai_bekerja_mandiri' => 'required|numeric|min:0|max:100',
            'nilai_ketelitian' => 'required|numeric|min:0|max:100',
            'nilai_belajar_menyerap' => 'required|numeric|min:0|max:100',
            'nilai_analisa_merancang' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'nullable|string',
            'surat_nilai' => 'nullable|file|mimes:pdf|max:2048',
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

        // Calculate jumlah and rata-rata
        $jumlahNilai = $data['nilai_keputusan_pemberi'] + $data['nilai_disiplin'] +
            $data['nilai_prioritas'] + $data['nilai_tepat_waktu'] +
            $data['nilai_bekerja_sama'] + $data['nilai_bekerja_mandiri'] +
            $data['nilai_ketelitian'] + $data['nilai_belajar_menyerap'] +
            $data['nilai_analisa_merancang'];

        $rataRata = $jumlahNilai / 9;

        // Konversi nilai
        $konversi = PenilaianAkhir::konversiNilai($rataRata);

        // Handle file upload
        $suratNilaiPath = null;
        if ($request->hasFile('surat_nilai')) {
            $suratNilaiPath = $request->file('surat_nilai')->store('surat_nilai', 'public');
        }

        PenilaianAkhir::create([
            'data_magang_id' => $data['data_magang_id'],
            'nilai_keputusan_pemberi' => $data['nilai_keputusan_pemberi'],
            'nilai_disiplin' => $data['nilai_disiplin'],
            'nilai_prioritas' => $data['nilai_prioritas'],
            'nilai_tepat_waktu' => $data['nilai_tepat_waktu'],
            'nilai_bekerja_sama' => $data['nilai_bekerja_sama'],
            'nilai_bekerja_mandiri' => $data['nilai_bekerja_mandiri'],
            'nilai_ketelitian' => $data['nilai_ketelitian'],
            'nilai_belajar_menyerap' => $data['nilai_belajar_menyerap'],
            'nilai_analisa_merancang' => $data['nilai_analisa_merancang'],
            'jumlah_nilai' => $jumlahNilai,
            'rata_rata' => $rataRata,
            'nilai_huruf' => $konversi['huruf'],
            'bobot' => $konversi['bobot'],
            'keterangan' => $konversi['keterangan'],
            'penilai_id' => $user->id,
            'tanggal_penilaian' => now(),
            'umpan_balik' => $data['umpan_balik'],
            'path_surat_nilai' => $suratNilaiPath,
        ]);

        // Update status to 'selesai' and workflow_status to 'evaluated'
        $dataMagang->update([
            'status' => 'selesai',
            'workflow_status' => 'evaluated'
        ]);

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
            'nilai_keputusan_pemberi' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_prioritas' => 'required|numeric|min:0|max:100',
            'nilai_tepat_waktu' => 'required|numeric|min:0|max:100',
            'nilai_bekerja_sama' => 'required|numeric|min:0|max:100',
            'nilai_bekerja_mandiri' => 'required|numeric|min:0|max:100',
            'nilai_ketelitian' => 'required|numeric|min:0|max:100',
            'nilai_belajar_menyerap' => 'required|numeric|min:0|max:100',
            'nilai_analisa_merancang' => 'required|numeric|min:0|max:100',
            'umpan_balik' => 'nullable|string',
        ]);

        // Calculate jumlah and rata-rata
        $jumlahNilai = $data['nilai_keputusan_pemberi'] + $data['nilai_disiplin'] +
            $data['nilai_prioritas'] + $data['nilai_tepat_waktu'] +
            $data['nilai_bekerja_sama'] + $data['nilai_bekerja_mandiri'] +
            $data['nilai_ketelitian'] + $data['nilai_belajar_menyerap'] +
            $data['nilai_analisa_merancang'];

        $rataRata = $jumlahNilai / 9;

        // Konversi nilai
        $konversi = PenilaianAkhir::konversiNilai($rataRata);

        $updateData = [
            'data_magang_id' => $data['data_magang_id'],
            'nilai_keputusan_pemberi' => $data['nilai_keputusan_pemberi'],
            'nilai_disiplin' => $data['nilai_disiplin'],
            'nilai_prioritas' => $data['nilai_prioritas'],
            'nilai_tepat_waktu' => $data['nilai_tepat_waktu'],
            'nilai_bekerja_sama' => $data['nilai_bekerja_sama'],
            'nilai_bekerja_mandiri' => $data['nilai_bekerja_mandiri'],
            'nilai_ketelitian' => $data['nilai_ketelitian'],
            'nilai_belajar_menyerap' => $data['nilai_belajar_menyerap'],
            'nilai_analisa_merancang' => $data['nilai_analisa_merancang'],
            'jumlah_nilai' => $jumlahNilai,
            'rata_rata' => $rataRata,
            'nilai_huruf' => $konversi['huruf'],
            'bobot' => $konversi['bobot'],
            'keterangan' => $konversi['keterangan'],
            'umpan_balik' => $data['umpan_balik'],
        ];

        $penilaian->update($updateData);

        // Ensure status is still 'selesai'
        $penilaian->dataMagang->update(['status' => 'selesai']);

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

    public function print($id)
    {
        $penilaian = PenilaianAkhir::with([
            'dataMagang.profilPeserta',
            'dataMagang.pembimbing',
            'penilai'
        ])->findOrFail($id);

        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang || $penilaian->data_magang_id !== $dataMagang->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            if ($penilaian->dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('magang.penilaian.print', compact('penilaian'));
    }
}
