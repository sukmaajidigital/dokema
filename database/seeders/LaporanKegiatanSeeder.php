<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LaporanKegiatan;
use App\Models\DataMagang;
use Carbon\Carbon;

class LaporanKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataMagangs = DataMagang::where('status', 'diterima')->get();
        $statusVerifikasi = ['menunggu', 'disetujui', 'revisi'];

        $kegiatanTemplates = [
            'Mengikuti orientasi dan pengenalan lingkungan kerja perusahaan',
            'Mempelajari sistem informasi yang digunakan perusahaan',
            'Menganalisis kebutuhan sistem untuk pengembangan aplikasi',
            'Membuat dokumentasi teknis dari sistem yang ada',
            'Melakukan testing dan debugging pada aplikasi web',
            'Mengembangkan fitur baru pada sistem manajemen data',
            'Melakukan maintenance dan update database perusahaan',
            'Berpartisipasi dalam meeting tim pengembangan',
            'Membuat laporan progress pengembangan sistem',
            'Melakukan riset teknologi terbaru untuk implementasi',
            'Mempelajari framework dan tools development',
            'Mengikuti training internal perusahaan',
            'Membantu troubleshooting technical issues',
            'Menganalisis performa sistem dan memberikan rekomendasi',
            'Berkolaborasi dengan tim UI/UX untuk interface design'
        ];

        $catatanVerifikasi = [
            'Laporan sudah baik dan sesuai dengan kegiatan yang dilakukan.',
            'Perlu penambahan detail pada bagian analisis sistem.',
            'Sudah cukup lengkap, lanjutkan dengan konsistensi pelaporan.',
            'Mohon tambahkan screenshot atau dokumentasi visual.',
            'Laporan perlu diperjelas pada bagian kesimpulan.',
            'Sangat baik, detail kegiatan sudah lengkap dan jelas.',
            'Perlu menambahkan refleksi pembelajaran dari kegiatan ini.'
        ];

        foreach ($dataMagangs as $dataMagang) {
            $tanggalMulai = Carbon::parse($dataMagang->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($dataMagang->tanggal_selesai);
            $totalHari = $tanggalMulai->diffInDays($tanggalSelesai);

            // Buat laporan setiap 3-7 hari sekali
            $jumlahLaporan = min(rand(3, 12), floor($totalHari / 3));

            for ($i = 0; $i < $jumlahLaporan; $i++) {
                $tanggalLaporan = $tanggalMulai->copy()->addDays($i * rand(3, 7));

                // Skip jika tanggal laporan melebihi tanggal selesai magang
                if ($tanggalLaporan->greaterThan($tanggalSelesai) || $tanggalLaporan->greaterThan(Carbon::now())) {
                    continue;
                }

                // Status verifikasi dengan probabilitas
                $randomStatus = rand(1, 100);
                if ($randomStatus <= 60) {
                    $status = 'disetujui';
                } elseif ($randomStatus <= 85) {
                    $status = 'menunggu';
                } else {
                    $status = 'revisi';
                }

                LaporanKegiatan::create([
                    'data_magang_id' => $dataMagang->id,
                    'tanggal_laporan' => $tanggalLaporan->format('Y-m-d'),
                    'deskripsi' => $kegiatanTemplates[array_rand($kegiatanTemplates)] .
                        '. Aktivitas ini memberikan pengalaman berharga dalam memahami proses bisnis perusahaan dan meningkatkan kemampuan teknis.',
                    'path_lampiran' => rand(1, 100) <= 70 ? 'documents/lampiran_laporan_' . uniqid() . '.pdf' : null,
                    'status_verifikasi' => $status,
                    'catatan_verifikasi' => $status !== 'menunggu' ? $catatanVerifikasi[array_rand($catatanVerifikasi)] : null,
                    'waktu_verifikasi' => $status !== 'menunggu' ? $tanggalLaporan->addDays(rand(1, 3)) : null,
                ]);
            }
        }
    }
}
