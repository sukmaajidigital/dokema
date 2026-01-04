<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin/HR Users
        User::create([
            'name' => 'Admin HR',
            'email' => 'admin@dokema.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
            'email_verified_at' => now(),
        ]);

        // Pembimbing Users
        User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi.santoso@dokema.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ir. Sari Wulandari',
            'email' => 'sari.wulandari@dokema.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ahmad Rizki, S.T.',
            'email' => 'ahmad.rizki@dokema.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
            'email_verified_at' => now(),
        ]);
    }
}
