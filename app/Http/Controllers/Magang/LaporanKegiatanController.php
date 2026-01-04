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
        // Filter laporan berdasarkan role pengguna (Issue #5)
        if (Auth::user()->role === 'magang') {
            // Peserta hanya bisa lihat laporan milik sendiri
            $profilPeserta = Auth::user()->profilPeserta;
            if (!$profilPeserta) {
                return view('magang.laporan.index', ['laporan' => []]);
            }

            // dataMagang is hasMany, so get first record
            $dataMagang = $profilPeserta->dataMagang()->first();
            if (!$dataMagang) {
                return view('magang.laporan.index', ['laporan' => []]);
            }
            $laporan = $dataMagang->laporanKegiatan()->latest()->paginate(10);
        } elseif (Auth::user()->role === 'pembimbing') {
            // Pembimbing hanya bisa lihat laporan dari peserta yang dibimbing
            $laporan = LaporanKegiatan::whereIn(
                'data_magang_id',
                Auth::user()->magangDibimbing->pluck('id')
            )->latest()->paginate(10);
        } else {
            // HR bisa lihat semua laporan
            $laporan = LaporanKegiatan::latest()->paginate(10);
        }

        return view('magang.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $user = Auth::user();

        // Get data_magang based on role
        if ($user->role === 'magang') {
            // Magang only create for their own record
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang) {
                return redirect()->route('laporan.index')->with('error', 'Data magang tidak ditemukan');
            }
            return view('magang.laporan.create', compact('dataMagang'));
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing can create for peserta they supervise
            $dataMagangList = DataMagang::where('pembimbing_id', $user->id)
                ->with('profilPeserta')
                ->get();
            return view('magang.laporan.create', compact('dataMagangList'));
        } else {
            // HR can create for any peserta
            $dataMagangList = DataMagang::with('profilPeserta')->get();
            return view('magang.laporan.create', compact('dataMagangList'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation rules depend on role
        if ($user->role === 'magang') {
            $data = $request->validate([
                'tanggal_laporan' => 'required|date',
                'deskripsi' => 'required|string|min:20',
                'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Auto-fill data_magang_id for magang
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang) {
                return redirect()->back()->with('error', 'Data magang tidak ditemukan');
            }
            $data['data_magang_id'] = $dataMagang->id;
        } else {
            $data = $request->validate([
                'data_magang_id' => 'required|exists:data_magang,id',
                'tanggal_laporan' => 'required|date',
                'deskripsi' => 'required|string|min:20',
                'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Verify pembimbing has access
            if ($user->role === 'pembimbing') {
                $dataMagang = DataMagang::where('id', $data['data_magang_id'])
                    ->where('pembimbing_id', $user->id)
                    ->first();
                if (!$dataMagang) {
                    abort(403, 'Unauthorized');
                }
            }
        }

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('laporan/lampiran', 'public');
        }

        LaporanKegiatan::create([
            'data_magang_id' => $data['data_magang_id'],
            'tanggal_laporan' => $data['tanggal_laporan'],
            'deskripsi' => $data['deskripsi'],
            'path_lampiran' => $lampiranPath,
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat');
    }

    public function edit($id)
    {
        $laporan = LaporanKegiatan::with('dataMagang.profilPeserta')->findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang || $laporan->data_magang_id !== $dataMagang->id) {
                abort(403, 'Unauthorized');
            }
            return view('magang.laporan.edit', compact('laporan'));
        } elseif ($user->role === 'pembimbing') {
            if ($laporan->dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            $dataMagangList = DataMagang::where('pembimbing_id', $user->id)
                ->with('profilPeserta')
                ->get();
            return view('magang.laporan.edit', compact('laporan', 'dataMagangList'));
        } else {
            // HR can edit any
            $dataMagangList = DataMagang::with('profilPeserta')->get();
            return view('magang.laporan.edit', compact('laporan', 'dataMagangList'));
        }
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang || $laporan->data_magang_id !== $dataMagang->id) {
                abort(403, 'Unauthorized');
            }
            // Magang cannot change data_magang_id
            $data = $request->validate([
                'tanggal_laporan' => 'required|date',
                'deskripsi' => 'required|string|min:20',
                'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $data['data_magang_id'] = $dataMagang->id;
        } else {
            // Pembimbing/HR can change data_magang_id
            $data = $request->validate([
                'data_magang_id' => 'required|exists:data_magang,id',
                'tanggal_laporan' => 'required|date',
                'deskripsi' => 'required|string|min:20',
                'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Verify pembimbing access
            if ($user->role === 'pembimbing') {
                if ($laporan->dataMagang->pembimbing_id !== $user->id) {
                    abort(403, 'Unauthorized');
                }
                // Verify new data_magang_id also belongs to pembimbing
                $newDataMagang = DataMagang::where('id', $data['data_magang_id'])
                    ->where('pembimbing_id', $user->id)
                    ->first();
                if (!$newDataMagang) {
                    abort(403, 'Unauthorized');
                }
            }
        }

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

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diupdate');
    }

    public function destroy($id)
    {
        $laporan = LaporanKegiatan::findOrFail($id);
        $user = Auth::user();

        // Verify access
        if ($user->role === 'magang') {
            $dataMagang = $user->profilPeserta->dataMagang()->first();
            if (!$dataMagang || $laporan->data_magang_id !== $dataMagang->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            if ($laporan->dataMagang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

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
