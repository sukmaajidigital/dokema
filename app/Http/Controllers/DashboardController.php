<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataMagang;
use App\Models\ProfilPeserta;
use App\Models\LaporanKegiatan;
use App\Models\LogBimbingan;
use App\Models\PenilaianAkhir;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Role-based data filtering
        if ($user->role === 'magang') {
            return $this->magangDashboard($user);
        } elseif ($user->role === 'pembimbing') {
            return $this->pembimbingDashboard($user);
        } else {
            return $this->hrDashboard();
        }
    }

    private function hrDashboard()
    {
        // HR: Lihat semua data
        $totalPeserta = ProfilPeserta::count();
        $totalMagang = DataMagang::count();
        $magangAktif = DataMagang::where('status', 'diterima')->count();
        $magangMenunggu = DataMagang::where('status', 'menunggu')->count();
        $totalLaporan = LaporanKegiatan::count();
        $totalBimbingan = LogBimbingan::count();
        $totalUser = User::count();

        // Magang yang akan berakhir dalam 30 hari
        $magangAkanBerakhir = DataMagang::where('tanggal_selesai', '<=', now()->addDays(30))
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'diterima')
            ->with('profilPeserta')
            ->latest()
            ->take(5)
            ->get();

        // Laporan terbaru
        $laporanTerbaru = LaporanKegiatan::with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Bimbingan terbaru
        $bimbinganTerbaru = LogBimbingan::with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Status magang chart data
        $statusMagang = DataMagang::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'totalPeserta',
            'totalMagang',
            'magangAktif',
            'magangMenunggu',
            'totalLaporan',
            'totalBimbingan',
            'totalUser',
            'magangAkanBerakhir',
            'laporanTerbaru',
            'bimbinganTerbaru',
            'statusMagang'
        ));
    }

    private function pembimbingDashboard($user)
    {
        // Pembimbing: Hanya lihat data peserta yang dibimbing
        $magangDibimbingIds = DataMagang::where('pembimbing_id', $user->id)->pluck('id');

        $totalPeserta = DataMagang::where('pembimbing_id', $user->id)->count();
        $totalMagang = DataMagang::where('pembimbing_id', $user->id)->count();
        $magangAktif = DataMagang::where('pembimbing_id', $user->id)
            ->where('status', 'diterima')
            ->count();
        $magangMenunggu = 0; // Pembimbing tidak approve
        $totalLaporan = LaporanKegiatan::whereIn('data_magang_id', $magangDibimbingIds)->count();
        $totalBimbingan = LogBimbingan::whereIn('data_magang_id', $magangDibimbingIds)->count();
        $totalUser = null; // Tidak relevan untuk pembimbing

        // Magang yang akan berakhir dalam 30 hari
        $magangAkanBerakhir = DataMagang::where('pembimbing_id', $user->id)
            ->where('tanggal_selesai', '<=', now()->addDays(30))
            ->where('tanggal_selesai', '>=', now())
            ->where('status', 'diterima')
            ->with('profilPeserta')
            ->latest()
            ->take(5)
            ->get();

        // Laporan terbaru dari peserta yang dibimbing
        $laporanTerbaru = LaporanKegiatan::whereIn('data_magang_id', $magangDibimbingIds)
            ->with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Bimbingan terbaru
        $bimbinganTerbaru = LogBimbingan::whereIn('data_magang_id', $magangDibimbingIds)
            ->with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Status magang chart data
        $statusMagang = DataMagang::where('pembimbing_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'totalPeserta',
            'totalMagang',
            'magangAktif',
            'magangMenunggu',
            'totalLaporan',
            'totalBimbingan',
            'totalUser',
            'magangAkanBerakhir',
            'laporanTerbaru',
            'bimbinganTerbaru',
            'statusMagang'
        ));
    }

    private function magangDashboard($user)
    {
        // Magang: Hanya lihat data diri sendiri
        $profilPeserta = $user->profilPeserta;

        if (!$profilPeserta) {
            return view('dashboard', [
                'totalPeserta' => 0,
                'totalMagang' => 0,
                'magangAktif' => 0,
                'magangMenunggu' => 0,
                'totalLaporan' => 0,
                'totalBimbingan' => 0,
                'totalUser' => null,
                'magangAkanBerakhir' => collect(),
                'laporanTerbaru' => collect(),
                'bimbinganTerbaru' => collect(),
                'statusMagang' => [],
                'dataMagang' => null,
            ]);
        }

        $dataMagang = $profilPeserta->dataMagang()->first();

        if (!$dataMagang) {
            return view('dashboard', [
                'totalPeserta' => 1,
                'totalMagang' => 0,
                'magangAktif' => 0,
                'magangMenunggu' => 0,
                'totalLaporan' => 0,
                'totalBimbingan' => 0,
                'totalUser' => null,
                'magangAkanBerakhir' => collect(),
                'laporanTerbaru' => collect(),
                'bimbinganTerbaru' => collect(),
                'statusMagang' => [],
                'dataMagang' => null,
            ]);
        }

        $totalPeserta = 1; // Diri sendiri
        $totalMagang = 1;
        $magangAktif = $dataMagang->status === 'diterima' ? 1 : 0;
        $magangMenunggu = $dataMagang->status === 'menunggu' ? 1 : 0;
        $totalLaporan = $dataMagang->laporanKegiatan()->count();
        $totalBimbingan = $dataMagang->logBimbingan()->count();
        $totalUser = null; // Tidak relevan untuk magang

        // Magang akan berakhir (hanya diri sendiri)
        $magangAkanBerakhir = collect();
        if (
            $dataMagang->tanggal_selesai &&
            $dataMagang->tanggal_selesai <= now()->addDays(30) &&
            $dataMagang->tanggal_selesai >= now() &&
            $dataMagang->status === 'diterima'
        ) {
            $magangAkanBerakhir = collect([$dataMagang->load('profilPeserta')]);
        }

        // Laporan terbaru (diri sendiri)
        $laporanTerbaru = $dataMagang->laporanKegiatan()
            ->with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Bimbingan terbaru (diri sendiri)
        $bimbinganTerbaru = $dataMagang->logBimbingan()
            ->with(['dataMagang.profilPeserta'])
            ->latest()
            ->take(5)
            ->get();

        // Status magang (hanya diri sendiri)
        $statusMagang = [$dataMagang->status => 1];

        return view('dashboard', compact(
            'totalPeserta',
            'totalMagang',
            'magangAktif',
            'magangMenunggu',
            'totalLaporan',
            'totalBimbingan',
            'totalUser',
            'magangAkanBerakhir',
            'laporanTerbaru',
            'bimbinganTerbaru',
            'statusMagang',
            'dataMagang'
        ));
    }
}
