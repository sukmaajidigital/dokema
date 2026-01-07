<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Penilaian Praktek Kerja Lapangan - {{ $penilaian->dataMagang->profilPeserta->nama_peserta }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }

        .kop-text {
            flex: 1;
        }

        .kop-text h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 2px;
        }

        .kop-text h2 {
            font-size: 20pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .kop-text p {
            font-size: 9pt;
            margin: 1px 0;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0 20px 0;
        }

        .intro-text {
            text-align: justify;
            margin-bottom: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 200px;
            flex-shrink: 0;
        }

        .info-separator {
            width: 20px;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background-color: #e5e7eb;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        td {
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            width: 250px;
            text-align: center;
        }

        .signature-space {
            height: 80px;
            margin: 20px 0;
        }

        .underline {
            text-decoration: underline;
        }

        .conversion-table {
            margin-top: 20px;
            font-size: 10pt;
        }

        .conversion-table td {
            padding: 5px 8px;
        }

        .footer-note {
            font-size: 10pt;
            margin-top: 30px;
            font-style: italic;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #1e40af;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background-color: #1e3a8a;
        }
    </style>
</head>

<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak Dokumen</button>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="{{ asset('logo/telkom_logo.png') }}" alt="Logo DOKEMA" class="kop-logo">
        <div class="kop-text">
            <h1>TELKOM AKSES</h1>
            <p>Jl. Contoh Alamat No. 123 Kota, Provinsi 12345 Indonesia</p>
            <p>T: (0291) 123456, E: info@dokema.id, W: dokema.id</p>
        </div>
    </div>

    <!-- Judul -->
    <div class="title">
        FORMULIR PENILAIAN PRAKTEK KERJA LAPANGAN
    </div>

    <!-- Intro Text -->
    <div class="intro-text">
        Dengan ini kami menyatakan bahwa mahasiswa berikut:
    </div>

    <!-- Info Peserta -->
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nama Penyelia</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $penilaian->penilai ? $penilaian->penilai->name : '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama Instansi</div>
            <div class="info-separator">:</div>
            <div class="info-value">PT Telkom Akses</div>
        </div>
        {{-- <div class="info-row">
            <div class="info-label">Judul Praktek Kerja Lapangan</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $penilaian->dataMagang->judul_kegiatan ?? 'Sistem Manajemen Magang' }}</div>
        </div> --}}
        <div class="info-row">
            <div class="info-label">Tanggal Kerangka Acuan</div>
            <div class="info-separator">:</div>
            <div class="info-value">
                {{ \Carbon\Carbon::parse($penilaian->dataMagang->tanggal_mulai)->format('d F Y') }} -
                {{ \Carbon\Carbon::parse($penilaian->dataMagang->tanggal_selesai)->format('d F Y') }}
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama Mahasiswa</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $penilaian->dataMagang->profilPeserta->nama_peserta }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nomor Pokok Mahasiswa</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $penilaian->dataMagang->profilPeserta->nim }}</div>
        </div>
    </div>

    <!-- Penjelasan -->
    <div class="intro-text">
        Dinyatakan telah menyelesaikan praktek kerja lapangan di instansi kami sesuai dengan
        kerangka acuan tertanggal diatas. Dengan mempertimbangkan segala aspek, baik dari segi bobot
        pekerjaan maupun pelaksanaan Praktek Kerja Lapangan, maka kami memutuskan bahwa yang
        bersangkutan telah menyelesaikan kewajibannya dengan hasil sebagai berikut.
    </div>

    <!-- Tabel Penilaian -->
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Materi Penilaian</th>
                <th style="width: 100px;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>Keputusan Pemberi Praktek Kerja Lapangan</td>
                <td class="text-center">{{ number_format($penilaian->nilai_keputusan_pemberi, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Disiplin</td>
                <td class="text-center">{{ number_format($penilaian->nilai_disiplin, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>Kemampuan memilih prioritas</td>
                <td class="text-center">{{ number_format($penilaian->nilai_prioritas, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td>Tepat waktu</td>
                <td class="text-center">{{ number_format($penilaian->nilai_tepat_waktu, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">5</td>
                <td>Kemampuan bekerja sama</td>
                <td class="text-center">{{ number_format($penilaian->nilai_bekerja_sama, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">6</td>
                <td>Kemampuan bekerja mandiri</td>
                <td class="text-center">{{ number_format($penilaian->nilai_bekerja_mandiri, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">7</td>
                <td>Ketelitian</td>
                <td class="text-center">{{ number_format($penilaian->nilai_ketelitian, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">8</td>
                <td>Kemampuan belajar dan kemampuan menyerap hal baru</td>
                <td class="text-center">{{ number_format($penilaian->nilai_belajar_menyerap, 2) }}</td>
            </tr>
            <tr>
                <td class="text-center">9</td>
                <td>Kemampuan analisa merancang</td>
                <td class="text-center">{{ number_format($penilaian->nilai_analisa_merancang, 2) }}</td>
            </tr>
            <tr class="bold">
                <td colspan="2" class="text-right">Jumlah</td>
                <td class="text-center">{{ number_format($penilaian->jumlah_nilai, 2) }}</td>
            </tr>
            <tr class="bold">
                <td colspan="2" class="text-right">Rata-rata</td>
                <td class="text-center">{{ number_format($penilaian->rata_rata, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Konversi Nilai -->
    <div>
        <p><strong>Dengan memperhatikan beberapa pertimbangan penilaian diatas, maka penyelia memberikan nilai:</strong></p>
        <p style="margin: 10px 0 20px 20px; font-size: 14pt;">
            <strong>{{ $penilaian->nilai_huruf }}</strong> dengan rata-rata <strong>{{ number_format($penilaian->rata_rata, 2) }}</strong> ({{ $penilaian->keterangan }})
        </p>
    </div>

    <!-- Tabel Konversi -->
    <table class="conversion-table">
        <thead>
            <tr>
                <th>Range Nilai</th>
                <th>Nilai</th>
                <th>Bobot</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">85 - 100</td>
                <td class="text-center">A</td>
                <td class="text-center">4</td>
                <td class="text-center">Memuaskan</td>
            </tr>
            <tr>
                <td class="text-center">75 - 84</td>
                <td class="text-center">AB</td>
                <td class="text-center">3,5</td>
                <td class="text-center">Sangat Baik</td>
            </tr>
            <tr>
                <td class="text-center">67 - 74</td>
                <td class="text-center">B</td>
                <td class="text-center">3</td>
                <td class="text-center">Baik</td>
            </tr>
            <tr>
                <td class="text-center">61 - 66</td>
                <td class="text-center">BC</td>
                <td class="text-center">2,5</td>
                <td class="text-center">Cukup Baik</td>
            </tr>
            <tr>
                <td class="text-center">55 - 60</td>
                <td class="text-center">C</td>
                <td class="text-center">2</td>
                <td class="text-center">Sedang</td>
            </tr>
            <tr>
                <td class="text-center">45 - 54</td>
                <td class="text-center">CD</td>
                <td class="text-center">1,5</td>
                <td class="text-center">Kurang</td>
            </tr>
            <tr>
                <td class="text-center">35 - 44</td>
                <td class="text-center">D</td>
                <td class="text-center">1</td>
                <td class="text-center">Sangat Kurang</td>
            </tr>
            <tr>
                <td class="text-center">0 - 34</td>
                <td class="text-center">E</td>
                <td class="text-center">0</td>
                <td class="text-center">Gagal</td>
            </tr>
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Kudus, {{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->format('d F Y') }}</p>
            <p>PT. Telkom Akses Kudus</p>
            <p>Penyelia</p>
            <div class="signature-space"></div>
            <p class="underline bold">{{ $penilaian->penilai ? $penilaian->penilai->name : '-' }}</p>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Manajemen Magang (DOKEMA)</p>
        <p>Tanggal cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }} WIB</p>
    </div>
</body>

</html>
