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

        // Magang Users
        $magangUsers = [
            ['name' => 'Andi Pratama', 'email' => 'andi.pratama@gmail.com'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@gmail.com'],
            ['name' => 'Reza Maulana', 'email' => 'reza.maulana@gmail.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@gmail.com'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@gmail.com'],
            ['name' => 'Maya Sari', 'email' => 'maya.sari@gmail.com'],
            ['name' => 'Dimas Aditya', 'email' => 'dimas.aditya@gmail.com'],
            ['name' => 'Putri Amelia', 'email' => 'putri.amelia@gmail.com'],
            ['name' => 'Ryan Kurniawan', 'email' => 'ryan.kurniawan@gmail.com'],
            ['name' => 'Indah Permata', 'email' => 'indah.permata@gmail.com'],
        ];

        foreach ($magangUsers as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => 'magang',
                'email_verified_at' => now(),
            ]);
        }
    }
}
