<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LogBimbingan;
use App\Models\DataMagang;
use Carbon\Carbon;

class LogBimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataMagangs = DataMagang::where('status', 'diterima')->get();

        $catatanPesertaTemplates = [
            'Hari ini saya mendapat bimbingan mengenai cara mengoptimalkan query database. Pembimbing memberikan tips yang sangat berguna.',
            'Diskusi tentang best practices dalam pengembangan aplikasi web. Saya mendapat insight baru tentang clean code.',
            'Pembimbing memberikan feedback terhadap progress pengembangan sistem yang saya kerjakan. Ada beberapa poin yang perlu diperbaiki.',
            'Konsultasi mengenai implementasi fitur baru. Pembimbing memberikan arahan yang jelas untuk langkah selanjutnya.',
            'Review hasil testing aplikasi bersama pembimbing. Ditemukan beberapa bug yang perlu diperbaiki.',
            'Pembimbing menjelaskan tentang metodologi pengembangan software yang digunakan di perusahaan.',
            'Diskusi tentang tantangan yang saya hadapi dalam memahami legacy code. Pembimbing memberikan tips troubleshooting.',
            'Evaluasi terhadap dokumentasi teknis yang telah saya buat. Ada beberapa bagian yang perlu diperjelas.',
            'Pembimbing memberikan guidance tentang cara berinteraksi dengan client dan stakeholder.',
            'Konsultasi mengenai career path dan skill yang perlu dikembangkan untuk masa depan.'
        ];

        $catatanPembimbingTemplates = [
            'Peserta menunjukkan progress yang baik dalam memahami konsep database optimization. Perlu lebih banyak praktik.',
            'Kemampuan coding peserta sudah cukup baik. Disarankan untuk lebih memperhatikan code readability.',
            'Peserta aktif bertanya dan eager to learn. Progress pengembangan sistem sesuai timeline.',
            'Perlu improvement dalam hal communication skill, terutama dalam presentasi teknis.',
            'Peserta sudah mulai memahami business logic. Disarankan untuk lebih fokus pada testing.',
            'Good analytical thinking. Peserta mampu mengidentifikasi masalah dengan baik.',
            'Troubleshooting skill perlu ditingkatkan. Berikan lebih banyak case study.',
            'Dokumentasi yang dibuat sudah cukup detail. Perlu konsisten dalam format penulisan.',
            'Peserta menunjukkan soft skill yang baik dalam berinteraksi dengan tim.',
            'Overall performance memuaskan. Siap untuk mengambil tanggung jawab yang lebih besar.'
        ];

        foreach ($dataMagangs as $dataMagang) {
            $tanggalMulai = Carbon::parse($dataMagang->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($dataMagang->tanggal_selesai);
            $totalHari = $tanggalMulai->diffInDays($tanggalSelesai);

            // Buat log bimbingan setiap 1-2 minggu sekali
            $jumlahBimbingan = min(rand(2, 8), floor($totalHari / 10));

            for ($i = 0; $i < $jumlahBimbingan; $i++) {
                $tanggalBimbingan = $tanggalMulai->copy()->addDays($i * rand(7, 14));

                // Skip jika tanggal bimbingan melebihi tanggal selesai magang atau belum terjadi
                if ($tanggalBimbingan->greaterThan($tanggalSelesai) || $tanggalBimbingan->greaterThan(Carbon::now())) {
                    continue;
                }

                // Random waktu bimbingan (jam kerja)
                $waktuBimbingan = $tanggalBimbingan->copy()->setTime(rand(9, 16), rand(0, 59));

                LogBimbingan::create([
                    'data_magang_id' => $dataMagang->id,
                    'waktu_bimbingan' => $waktuBimbingan,
                    'catatan_peserta' => rand(1, 100) <= 80 ? $catatanPesertaTemplates[array_rand($catatanPesertaTemplates)] : null,
                    'catatan_pembimbing' => rand(1, 100) <= 85 ? $catatanPembimbingTemplates[array_rand($catatanPembimbingTemplates)] : null,
                ]);
            }
        }
    }
}
