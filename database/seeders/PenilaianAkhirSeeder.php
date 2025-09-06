<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PenilaianAkhir;
use App\Models\DataMagang;
use Carbon\Carbon;

class PenilaianAkhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya buat penilaian untuk magang yang sudah selesai atau hampir selesai
        $dataMagangs = DataMagang::where('status', 'diterima')
            ->where('tanggal_selesai', '<=', Carbon::now()->addDays(30))
            ->get();

        $umpanBalikTemplates = [
            'Peserta magang menunjukkan dedikasi yang tinggi selama periode magang. Kemampuan teknis berkembang dengan baik, mampu menyelesaikan tugas-tugas yang diberikan dengan hasil memuaskan. Disarankan untuk terus mengembangkan soft skill communication.',

            'Performance yang sangat baik selama magang. Peserta aktif bertanya, cepat belajar, dan mampu beradaptasi dengan lingkungan kerja. Code quality yang dihasilkan sudah sesuai standar perusahaan. Recommended untuk posisi junior developer.',

            'Peserta menunjukkan improvement yang signifikan dari awal hingga akhir periode magang. Awalnya masih perlu banyak guidance, namun di akhir periode sudah mampu bekerja mandiri. Team collaboration skill juga berkembang baik.',

            'Hasil kerja konsisten dan berkualitas. Peserta memiliki analytical thinking yang baik dalam problem solving. Dokumentasi yang dibuat juga lengkap dan rapi. Perlu sedikit improvement dalam time management.',

            'Peserta sangat proaktif dan memiliki inisiatif yang baik. Tidak hanya menyelesaikan tugas yang diberikan, tetapi juga memberikan ide-ide inovatif untuk improvement sistem. Attitude dan work ethic yang excellent.',

            'Good technical competency dan eagerness to learn. Peserta mampu mengikuti development process dengan baik. Perlu lebih confident dalam mempresentasikan hasil kerja di hadapan stakeholder.',

            'Overall performance memuaskan. Peserta mampu menyelesaikan project yang diberikan tepat waktu dengan kualitas yang baik. Recommended untuk melanjutkan career di bidang IT development.',

            'Peserta menunjukkan potential yang baik untuk berkembang lebih jauh. Learning curve yang cepat, mampu memahami business requirement dengan baik. Perlu lebih aktif dalam team discussion dan brainstorming session.'
        ];

        foreach ($dataMagangs as $dataMagang) {
            // Hanya 70% yang sudah mendapat penilaian
            if (rand(1, 100) <= 70) {
                // Nilai antara 3.0 - 4.0 (skala 4.0)
                $nilai = rand(300, 400) / 100;

                PenilaianAkhir::create([
                    'data_magang_id' => $dataMagang->id,
                    'nilai' => $nilai,
                    'umpan_balik' => $umpanBalikTemplates[array_rand($umpanBalikTemplates)],
                    'path_surat_nilai' => rand(1, 100) <= 80 ? 'documents/surat_nilai_' . $dataMagang->id . '.pdf' : null,
                ]);
            }
        }
    }
}
