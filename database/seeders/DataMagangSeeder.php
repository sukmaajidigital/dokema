<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataMagang;
use App\Models\ProfilPeserta;
use App\Models\User;
use Carbon\Carbon;

class DataMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profilPesertas = ProfilPeserta::all();
        $pembimbings = User::where('role', 'pembimbing')->get();
        $statuses = ['menunggu', 'diterima', 'ditolak'];

        foreach ($profilPesertas as $index => $profil) {
            // Variasi tanggal mulai dan selesai
            $tanggalMulai = Carbon::now()->subDays(rand(0, 120));
            $tanggalSelesai = $tanggalMulai->copy()->addDays(rand(30, 180));

            // Status dengan probabilitas: 70% diterima, 20% menunggu, 10% ditolak
            $randomStatus = rand(1, 100);
            if ($randomStatus <= 70) {
                $status = 'diterima';
            } elseif ($randomStatus <= 90) {
                $status = 'menunggu';
            } else {
                $status = 'ditolak';
            }

            DataMagang::create([
                'profil_peserta_id' => $profil->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'path_surat_permohonan' => 'documents/surat_permohonan_' . ($index + 1) . '.pdf',
                'path_surat_balasan' => $status === 'diterima' ? 'documents/surat_balasan_' . ($index + 1) . '.pdf' : null,
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'status' => $status,
            ]);
        }

        // Tambahan beberapa data magang dengan variasi tanggal
        for ($i = 0; $i < 5; $i++) {
            $tanggalMulai = Carbon::now()->addDays(rand(1, 30)); // Magang yang akan datang
            $tanggalSelesai = $tanggalMulai->copy()->addDays(rand(60, 120));

            DataMagang::create([
                'profil_peserta_id' => $profilPesertas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'path_surat_permohonan' => 'documents/surat_permohonan_future_' . ($i + 1) . '.pdf',
                'path_surat_balasan' => 'documents/surat_balasan_future_' . ($i + 1) . '.pdf',
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'status' => 'diterima',
            ]);
        }
    }
}
