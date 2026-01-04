<?php

namespace App\Http\Controllers\Magang;

use App\Models\DataMagang;
use App\Models\ProfilPeserta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataMagangController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Filter data magang berdasarkan role
        if ($user->role === 'magang') {
            // Magang only see their own data
            $profilPeserta = $user->profilPeserta;
            if (!$profilPeserta) {
                $magang = collect([]);
            } else {
                $magang = $profilPeserta->dataMagang()->with(['profilPeserta', 'pembimbing'])->get();
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only see assigned peserta
            $magang = DataMagang::where('pembimbing_id', $user->id)
                ->with(['profilPeserta', 'pembimbing'])
                ->get();
        } else {
            // HR sees all
            $magang = DataMagang::with(['profilPeserta', 'pembimbing'])->get();
        }

        return view('magang.magang.index', compact('magang'));
    }

    public function create()
    {
        $user = auth()->user();

        // Only HR can manually create data magang
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        $pembimbings = \App\Models\User::where('role', 'pembimbing')->get();
        // Only show profil peserta that don't have data magang yet
        $pesertas = \App\Models\ProfilPeserta::whereDoesntHave('dataMagang')->get();
        return view('magang.magang.create', compact('pembimbings', 'pesertas'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Only HR can manually create data magang
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'profil_peserta_id' => 'required|exists:profil_peserta,id|unique:data_magang,profil_peserta_id',
            'pembimbing_id' => 'nullable|exists:users,id',
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_balasan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:menunggu,diterima,ditolak',
            'workflow_status' => 'nullable|in:draft,submitted,under_review,approved,rejected,in_progress,completed,evaluated',
        ]);

        // Simpan file surat permohonan
        $permohonanPath = $request->file('surat_permohonan')->store('magang/surat_permohonan', 'public');

        // Simpan file surat balasan jika ada
        $balasanPath = null;
        if ($request->hasFile('surat_balasan')) {
            $balasanPath = $request->file('surat_balasan')->store('magang/surat_balasan', 'public');
        }

        DataMagang::create([
            'profil_peserta_id' => $data['profil_peserta_id'],
            'pembimbing_id' => $data['pembimbing_id'],
            'path_surat_permohonan' => $permohonanPath,
            'path_surat_balasan' => $balasanPath,
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'status' => $data['status'],
            'workflow_status' => $data['workflow_status'] ?? 'submitted',
        ]);

        return redirect()->route('magang.index')->with('success', 'Data magang berhasil dibuat');
    }

    public function edit($id)
    {
        $magang = DataMagang::with(['profilPeserta', 'pembimbing'])->findOrFail($id);
        $user = auth()->user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang can only view/edit their own data (limited fields)
            $profilPeserta = $user->profilPeserta;
            if (!$profilPeserta || $magang->profil_peserta_id !== $profilPeserta->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing can edit assigned peserta data (limited fields)
            if ($magang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }
        // HR can edit everything

        $pembimbings = \App\Models\User::where('role', 'pembimbing')->get();
        $pesertas = \App\Models\ProfilPeserta::all();
        return view('magang.magang.edit', compact('magang', 'pembimbings', 'pesertas'));
    }

    public function update(Request $request, $id)
    {
        $magang = DataMagang::findOrFail($id);
        $user = auth()->user();

        // Verify access and validate based on role
        if ($user->role === 'magang') {
            // Magang can only view, cannot update via this controller
            abort(403, 'Unauthorized - cannot update data magang');
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing can only update assigned peserta, limited fields
            if ($magang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
            // Pembimbing cannot change profil_peserta_id, pembimbing_id, or status
            $data = $request->validate([
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'surat_balasan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Keep original values
            $data['profil_peserta_id'] = $magang->profil_peserta_id;
            $data['pembimbing_id'] = $magang->pembimbing_id;
            $data['status'] = $magang->status;
            $data['workflow_status'] = $magang->workflow_status;
            $data['path_surat_permohonan'] = $magang->path_surat_permohonan;
        } else {
            // HR can update everything
            $data = $request->validate([
                'profil_peserta_id' => 'required|exists:profil_peserta,id',
                'pembimbing_id' => 'nullable|exists:users,id',
                'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'surat_balasan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'status' => 'required|in:menunggu,diterima,ditolak',
                'workflow_status' => 'nullable|in:draft,submitted,under_review,approved,rejected,in_progress,completed,evaluated',
            ]);

            // Update surat permohonan if uploaded
            if ($request->hasFile('surat_permohonan')) {
                $data['path_surat_permohonan'] = $request->file('surat_permohonan')->store('magang/surat_permohonan', 'public');
            } else {
                $data['path_surat_permohonan'] = $magang->path_surat_permohonan;
            }
        }

        // Update surat balasan if uploaded (both pembimbing and HR)
        if ($request->hasFile('surat_balasan')) {
            $balasanPath = $request->file('surat_balasan')->store('magang/surat_balasan', 'public');
        } else {
            $balasanPath = $magang->path_surat_balasan;
        }

        $data['path_surat_balasan'] = $balasanPath;

        $magang->update($data);
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil diupdate');
    }

    public function show($id)
    {
        $magang = DataMagang::with(['profilPeserta.user', 'pembimbing', 'laporanKegiatan', 'logBimbingan', 'penilaianAkhir'])->findOrFail($id);
        $user = auth()->user();

        // Verify access
        if ($user->role === 'magang') {
            // Magang only view their own data
            $profilPeserta = $user->profilPeserta;
            if (!$profilPeserta || $magang->profil_peserta_id !== $profilPeserta->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'pembimbing') {
            // Pembimbing only view assigned peserta
            if ($magang->pembimbing_id !== $user->id) {
                abort(403, 'Unauthorized');
            }
        }

        return view('magang.magang.show', compact('magang'));
    }

    public function destroy($id)
    {
        $user = auth()->user();

        // Only HR can delete data magang
        if ($user->role !== 'hr') {
            abort(403, 'Unauthorized');
        }

        $magang = DataMagang::findOrFail($id);
        $magang->delete();
        return redirect()->route('magang.index')->with('success', 'Data magang berhasil dihapus');
    }
}
