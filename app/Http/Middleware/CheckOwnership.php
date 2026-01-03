<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKegiatan;
use App\Models\PenilaianAkhir;
use App\Models\LogBimbingan;
use App\Models\DataMagang;

class CheckOwnership
{
    /**
     * Handle an incoming request.
     * Verify that authenticated user owns the requested resource
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Extract resource type and ID from route
        $routeName = $request->route()->getName();
        $id = $request->route('id');

        // For laporan routes
        if (str_contains($routeName, 'laporan')) {
            $laporan = LaporanKegiatan::find($id);
            if (!$laporan) {
                abort(404);
            }

            // Check ownership based on role
            if ($user->role === 'magang') {
                // Peserta hanya bisa akses laporan milik sendiri
                if ($laporan->dataMagang->profilPeserta->user_id !== $user->id) {
                    abort(403, 'Anda tidak memiliki akses ke laporan ini.');
                }
            } elseif ($user->role === 'pembimbing') {
                // Pembimbing hanya bisa akses laporan peserta yang dibimbing
                if ($laporan->dataMagang->pembimbing_id !== $user->id) {
                    abort(403, 'Anda bukan pembimbing untuk peserta ini.');
                }
            }
            // HR can access all
        }

        // For penilaian routes
        if (str_contains($routeName, 'penilaian')) {
            $penilaian = PenilaianAkhir::find($id);
            if (!$penilaian) {
                abort(404);
            }

            // Check ownership based on role
            if ($user->role === 'magang') {
                // Peserta hanya bisa lihat penilaian milik sendiri
                if ($penilaian->dataMagang->profilPeserta->user_id !== $user->id) {
                    abort(403, 'Anda tidak memiliki akses ke penilaian ini.');
                }
            } elseif ($user->role === 'pembimbing') {
                // Pembimbing hanya bisa akses penilaian peserta yang dibimbing
                if ($penilaian->dataMagang->pembimbing_id !== $user->id) {
                    abort(403, 'Anda bukan pembimbing untuk peserta ini.');
                }
            }
            // HR can access all
        }

        // For bimbingan routes
        if (str_contains($routeName, 'bimbingan')) {
            $bimbingan = LogBimbingan::find($id);
            if (!$bimbingan) {
                abort(404);
            }

            // Check ownership based on role
            if ($user->role === 'magang') {
                // Peserta hanya bisa akses log bimbingan milik sendiri
                if ($bimbingan->dataMagang->profilPeserta->user_id !== $user->id) {
                    abort(403, 'Anda tidak memiliki akses ke log bimbingan ini.');
                }
            } elseif ($user->role === 'pembimbing') {
                // Pembimbing hanya bisa akses log bimbingan peserta yang dibimbing
                if ($bimbingan->dataMagang->pembimbing_id !== $user->id) {
                    abort(403, 'Anda bukan pembimbing untuk peserta ini.');
                }
            }
            // HR can access all
        }

        // For magang routes
        if (str_contains($routeName, 'magang')) {
            $magang = DataMagang::find($id);
            if (!$magang) {
                abort(404);
            }

            // Check ownership based on role
            if ($user->role === 'magang') {
                // Peserta hanya bisa akses data magang milik sendiri
                if ($magang->profilPeserta->user_id !== $user->id) {
                    abort(403, 'Anda tidak memiliki akses ke data magang ini.');
                }
            } elseif ($user->role === 'pembimbing') {
                // Pembimbing hanya bisa akses data magang peserta yang dibimbing
                if ($magang->pembimbing_id !== $user->id) {
                    abort(403, 'Anda bukan pembimbing untuk peserta ini.');
                }
            }
            // HR can access all
        }

        return $next($request);
    }
}
