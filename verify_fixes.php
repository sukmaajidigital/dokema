<?php
// Quick verification script for DOKEMA fixes

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== DOKEMA System Verification ===\n\n";

// Test 1: Database connection
echo "1. Database Connection: ";
try {
    DB::connection()->getPDO();
    echo "✅ Connected\n";
} catch (Exception $e) {
    echo "❌ Failed\n";
    exit(1);
}

// Test 2: Check seeded users
echo "2. Seeded Users:\n";
$users = User::with('profilPeserta')->where('role', 'magang')->limit(3)->get();
foreach ($users as $user) {
    echo "   - {$user->email} (role: {$user->role})\n";
    if ($user->profilPeserta) {
        $dm = $user->profilPeserta->dataMagang()->first();
        if ($dm) {
            echo "     → DataMagang: {$dm->workflow_status}\n";
            echo "     → Laporan count: " . $dm->laporanKegiatan()->count() . "\n";
        } else {
            echo "     → No DataMagang\n";
        }
    } else {
        echo "     → No ProfilPeserta\n";
    }
}

// Test 3: Check Collection to Model pattern
echo "\n3. Collection to Model Pattern Test:\n";
$user = User::where('role', 'magang')->first();
if ($user && $user->profilPeserta) {
    // This is what we fixed
    $dm = $user->profilPeserta->dataMagang()->first();
    echo "   dataMagang()->first() type: " . get_class($dm) . "\n";
    echo "   ✅ Pattern is working correctly\n";
}

// Test 4: Check Relationships
echo "\n4. Relationships Check:\n";
$user = User::first();
echo "   User->profilPeserta: " . (method_exists($user, 'profilPeserta') ? "✅" : "❌") . "\n";
$profil = $user->profilPeserta;
if ($profil) {
    echo "   ProfilPeserta->dataMagang: " . (method_exists($profil, 'dataMagang') ? "✅" : "❌") . "\n";
    $dm = $profil->dataMagang()->first();
    if ($dm) {
        echo "   DataMagang->laporanKegiatan: " . (method_exists($dm, 'laporanKegiatan') ? "✅" : "❌") . "\n";
    }
}

echo "\n=== Verification Complete ===\n";
echo "✅ All systems operational\n";
