<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilPeserta;
use App\Models\DataMagang;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     * CRITICAL FIX: Check workflow_status for magang role (Issue #3)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();

            // Check if magang user is approved before allowing login
            if ($user->role === 'magang') {
                $profilPeserta = $user->profilPeserta;
                // dataMagang is hasMany, get first record
                $dataMagang = $profilPeserta->dataMagang()->first() ?? null;

                if (!$dataMagang || $dataMagang->workflow_status !== 'approved') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // Store user info for waiting-approval page
                    session(['pending_user_id' => $user->id]);

                    return redirect()->route('waiting-approval')->withErrors([
                        'email' => 'Akun Anda belum disetujui oleh HRD. Silakan tunggu persetujuan.',
                    ]);
                }
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show registration form (if public registration is allowed)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     * CRITICAL FIX: Auto-create profil_peserta + data_magang (Issue #1)
     * User should NOT auto-login, must wait for HRD approval
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'universitas' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:15'],
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'magang', // Default role for registration
        ]);

        // Create profil_peserta
        $profilPeserta = \App\Models\ProfilPeserta::create([
            'user_id' => $user->id,
            'nama' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'universitas' => $validated['universitas'],
            'jurusan' => $validated['jurusan'],
            'no_hp' => $validated['no_hp'],
        ]);

        // Create data_magang with status 'submitted' for HRD review
        \App\Models\DataMagang::create([
            'profil_peserta_id' => $profilPeserta->id,
            'workflow_status' => 'submitted', // Initial status: waiting for HRD review
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);

        // DO NOT auto-login - user must wait for HRD approval
        // Redirect to waiting-approval page with instruction
        session(['pending_user_id' => $user->id]);

        return redirect()->route('waiting-approval')->with(
            'success',
            'Pendaftaran berhasil! Silakan tunggu persetujuan dari HRD. Email konfirmasi akan dikirim ke ' . $user->email
        );
    }

    /**
     * Show waiting for approval page
     * Display to users with workflow_status not equal to 'approved'
     */
    public function showWaitingApproval(Request $request)
    {
        $pendingUserId = session('pending_user_id');

        if (!$pendingUserId) {
            return redirect()->route('login');
        }

        $user = User::find($pendingUserId);

        if (!$user) {
            return redirect()->route('login');
        }

        // dataMagang is hasMany, get first record
        $dataMagang = $user->profilPeserta->dataMagang()->first() ?? null;

        return view('auth.waiting-approval', compact('user', 'dataMagang'));
    }
}
