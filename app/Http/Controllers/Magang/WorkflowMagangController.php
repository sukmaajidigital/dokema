<?php

namespace App\Http\Controllers\Magang;

use App\Models\DataMagang;
use App\Models\ProfilPeserta;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class WorkflowMagangController extends Controller
{
    /**
     * Display workflow approval page
     */
    public function index()
    {
        // Get pending applications
        $pendingApplications = DataMagang::with(['profilPeserta.user'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        // Get quota information
        $quota = $this->checkQuota();

        // Get supervisors with current workload
        $supervisors = User::where('role', 'pembimbing')
            ->withCount(['magangDibimbing' => function ($query) {
                $query->where('status', 'diterima');
            }])
            ->orderBy('magang_dibimbing_count', 'asc')
            ->get();

        return view('workflow.approval', compact('pendingApplications', 'quota', 'supervisors'));
    }

    /**
     * Handle approval/rejection workflow
     */
    public function processApplication(Request $request, $magangId)
    {
        $magang = DataMagang::with(['profilPeserta.user', 'pembimbing'])->findOrFail($magangId);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string',
            'pembimbing_id' => 'required_if:action,approve|exists:users,id',
            'surat_balasan' => 'required_if:action,approve|file|mimes:pdf'
        ]);

        if ($request->action === 'approve') {
            // Approve the application
            $suratBalasanPath = $request->file('surat_balasan')->store('surat_balasan', 'public');

            $magang->update([
                'status' => 'diterima',
                'pembimbing_id' => $request->pembimbing_id,
                'path_surat_balasan' => $suratBalasanPath,
                'tanggal_persetujuan' => now()
            ]);

            // Send notification to student
            // Mail::to($magang->profilPeserta->user->email)->send(new MagangApproved($magang));

            return redirect()->back()->with('success', 'Permohonan magang telah disetujui dan pembimbing telah ditugaskan.');
        } else {
            // Reject the application  
            $magang->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->rejection_reason,
                'tanggal_penolakan' => now()
            ]);

            // Send notification to student
            // Mail::to($magang->profilPeserta->user->email)->send(new MagangRejected($magang));

            return redirect()->back()->with('success', 'Permohonan magang telah ditolak.');
        }
    }

    /**
     * Check quota for internship
     */
    public function checkQuota()
    {
        $activeInterns = DataMagang::where('status', 'diterima')
            ->whereBetween('tanggal_mulai', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $maxQuota = config('magang.max_quota', 20); // Set in config

        return [
            'current' => $activeInterns,
            'max' => $maxQuota,
            'available' => max(0, $maxQuota - $activeInterns),
            'is_full' => $activeInterns >= $maxQuota
        ];
    }

    /**
     * Auto-assign supervisor based on workload
     */
    public function autoAssignSupervisor()
    {
        $supervisors = User::where('role', 'pembimbing')
            ->withCount(['magangDibimbing' => function ($query) {
                $query->where('status', 'diterima');
            }])
            ->orderBy('magang_dibimbing_count', 'asc')
            ->first();

        return $supervisors;
    }
}
