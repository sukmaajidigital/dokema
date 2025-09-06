<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataMagang;
use App\Models\ProfilPeserta;
use App\Models\LaporanKegiatan;
use App\Models\LogBimbingan;
use App\Models\PenilaianAkhir;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Dashboard
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
}
