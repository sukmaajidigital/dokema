<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfilPeserta;
use App\Models\User;

class ProfilPesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universitas = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Brawijaya',
            'Universitas Padjadjaran',
            'Universitas Airlangga',
            'Universitas Diponegoro',
            'Universitas Sebelas Maret',
            'Universitas Hasanuddin'
        ];

        $jurusan = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Komputer',
            'Teknik Elektro',
            'Manajemen',
            'Akuntansi',
            'Teknik Industri',
            'Teknik Sipil',
            'Psikologi',
            'Komunikasi'
        ];

        $alamat = [
            'Jl. Merdeka No. 123, Jakarta Pusat',
            'Jl. Sudirman No. 456, Bandung',
            'Jl. Malioboro No. 789, Yogyakarta',
            'Jl. Ahmad Yani No. 321, Surabaya',
            'Jl. Pahlawan No. 654, Malang',
            'Jl. Dago No. 987, Bandung',
            'Jl. Raya Darmo No. 147, Surabaya',
            'Jl. Slamet Riyadi No. 258, Solo',
            'Jl. Veteran No. 369, Makassar',
            'Jl. Hayam Wuruk No. 159, Jakarta'
        ];

        // Ambil semua user dengan role magang
        $magangUsers = User::where('role', 'magang')->get();

        foreach ($magangUsers as $index => $user) {
            ProfilPeserta::create([
                'user_id' => $user->id,
                'nim' => '2021' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'universitas' => $universitas[array_rand($universitas)],
                'jurusan' => $jurusan[array_rand($jurusan)],
                'no_telepon' => '08' . rand(1000000000, 9999999999),
                'alamat' => $alamat[array_rand($alamat)],
            ]);
        }
    }
}
