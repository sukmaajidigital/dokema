<x-admin-layouts>
    <x-slot name="header">Detail Data Magang</x-slot>

    <div class="space-y-6">
        <!-- Informasi Peserta -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Informasi Peserta</h2>
                @if (Auth::user()->role !== 'magang')
                    <a href="{{ route('magang.edit', $magang->id) }}" class="text-blue-600 hover:text-blue-800">
                        <x-lucide-edit class="w-5 h-5" />
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Peserta</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->nama_peserta }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->user->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">NIM/NISN</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->nim }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">No. HP</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->no_hp }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Universitas</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->universitas }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Jurusan</label>
                    <p class="text-gray-900 mt-1">{{ $magang->profilPeserta->jurusan }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Magang -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Magang</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Pembimbing</label>
                    <p class="text-gray-900 mt-1">{{ $magang->pembimbing->name ?? 'Belum ditentukan' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <p class="mt-1">
                        @if ($magang->status === 'diterima')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Diterima</span>
                        @elseif($magang->status === 'selesai')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">Selesai</span>
                        @elseif($magang->status === 'ditolak')
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Ditolak</span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">Menunggu</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Workflow Status</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ ucfirst(str_replace('_', ' ', $magang->workflow_status ?? 'submitted')) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                    <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d F Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal Selesai</label>
                    <p class="text-gray-900 mt-1">{{ \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d F Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Durasi</label>
                    <p class="text-gray-900 mt-1">
                        {{ \Carbon\Carbon::parse($magang->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($magang->tanggal_selesai)) }} hari
                    </p>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Dokumen Magang</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Surat Permohonan -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Surat Permohonan</h3>
                    @if ($magang->path_surat_permohonan)
                        <div class="border rounded-lg overflow-hidden bg-white shadow-sm">
                            @php
                                $ext = strtolower(pathinfo($magang->path_surat_permohonan, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if ($isImage)
                                <img src="{{ asset('storage/' . $magang->path_surat_permohonan) }}" alt="Surat Permohonan" class="w-full h-64 object-contain bg-gray-50">
                            @else
                                <div class="p-8 text-center bg-gray-50">
                                    <svg class="mx-auto w-16 h-16 text-red-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Dokumen PDF</p>
                                    <p class="text-xs text-gray-500">{{ basename($magang->path_surat_permohonan) }}</p>
                                </div>
                            @endif
                            <div class="p-3 bg-gray-50 border-t flex justify-between items-center">
                                <span class="text-xs text-gray-600 font-medium">Surat Permohonan</span>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $magang->path_surat_permohonan) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">
                                        <x-lucide-eye class="w-3 h-3 mr-1" />
                                        Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $magang->path_surat_permohonan) }}" download class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200 transition">
                                        <x-lucide-download class="w-3 h-3 mr-1" />
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="border rounded-lg p-6 text-center text-gray-500 bg-gray-50">
                            <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm">Belum ada dokumen</p>
                        </div>
                    @endif
                </div>

                <!-- Surat Balasan -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Surat Balasan</h3>
                    @if ($magang->path_surat_balasan)
                        <div class="border rounded-lg overflow-hidden bg-white shadow-sm">
                            @php
                                $ext = strtolower(pathinfo($magang->path_surat_balasan, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if ($isImage)
                                <img src="{{ asset('storage/' . $magang->path_surat_balasan) }}" alt="Surat Balasan" class="w-full h-64 object-contain bg-gray-50">
                            @else
                                <div class="p-8 text-center bg-gray-50">
                                    <svg class="mx-auto w-16 h-16 text-red-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Dokumen PDF</p>
                                    <p class="text-xs text-gray-500">{{ basename($magang->path_surat_balasan) }}</p>
                                </div>
                            @endif
                            <div class="p-3 bg-gray-50 border-t flex justify-between items-center">
                                <span class="text-xs text-gray-600 font-medium">Surat Balasan</span>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $magang->path_surat_balasan) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">
                                        <x-lucide-eye class="w-3 h-3 mr-1" />
                                        Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $magang->path_surat_balasan) }}" download class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200 transition">
                                        <x-lucide-download class="w-3 h-3 mr-1" />
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="border rounded-lg p-6 text-center text-gray-500 bg-gray-50">
                            <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm">Belum ada dokumen</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Laporan Kegiatan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Laporan Kegiatan</h2>
                @if (Auth::user()->role === 'magang')
                    <a href="{{ route('laporan.create') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <x-lucide-plus class="w-5 h-5 mr-1" />
                        Tambah Laporan
                    </a>
                @endif
            </div>

            @if ($magang->laporanKegiatan && $magang->laporanKegiatan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($magang->laporanKegiatan->take(5) as $laporan)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ Str::limit($laporan->deskripsi, 50) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($laporan->status_verifikasi === 'verified')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Verified</span>
                                        @elseif($laporan->status_verifikasi === 'rejected')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Rejected</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('laporan.edit', $laporan->id) }}" class="text-blue-600 hover:text-blue-800">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($magang->laporanKegiatan->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('laporan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Semua Laporan ({{ $magang->laporanKegiatan->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada laporan kegiatan</p>
            @endif
        </div>

        <!-- Log Bimbingan -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Log Bimbingan</h2>
                @if (Auth::user()->role === 'pembimbing' || Auth::user()->role === 'hr')
                    <a href="{{ route('bimbingan.create') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <x-lucide-plus class="w-5 h-5 mr-1" />
                        Tambah Log
                    </a>
                @endif
            </div>

            @if ($magang->logBimbingan && $magang->logBimbingan->count() > 0)
                <div class="space-y-3">
                    @foreach ($magang->logBimbingan->take(5) as $log)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->waktu_bimbingan)->format('d F Y, H:i') }}
                                    </p>
                                    @if ($log->catatan_pembimbing)
                                        <p class="text-sm text-gray-600 mt-1">{{ $log->catatan_pembimbing }}</p>
                                    @endif
                                </div>
                                @if (Auth::user()->role !== 'magang')
                                    <a href="{{ route('bimbingan.edit', $log->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if ($magang->logBimbingan->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('bimbingan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Semua Log ({{ $magang->logBimbingan->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada log bimbingan</p>
            @endif
        </div>

        <!-- Penilaian Akhir -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Penilaian Akhir</h2>
                @if ((Auth::user()->role === 'pembimbing' || Auth::user()->role === 'hr') && !$magang->penilaianAkhir)
                    <a href="{{ route('penilaian.create') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <x-lucide-plus class="w-5 h-5 mr-1" />
                        Tambah Penilaian
                    </a>
                @endif
            </div>

            @if ($magang->penilaianAkhir)
                <!-- Summary Box -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hasil Penilaian</h3>
                            <div class="flex items-baseline gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Rata-rata</p>
                                    <p class="text-4xl font-bold text-blue-600">{{ number_format($magang->penilaianAkhir->rata_rata ?? 0, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nilai Huruf</p>
                                    <p class="text-4xl font-bold text-green-600">{{ $magang->penilaianAkhir->nilai_huruf ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Keterangan</p>
                                    <p class="text-lg font-semibold text-gray-800">{{ $magang->penilaianAkhir->keterangan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->role !== 'magang')
                            <div class="flex gap-2">
                                <a href="{{ route('penilaian.edit', $magang->penilaianAkhir->id) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white text-sm font-medium rounded hover:bg-yellow-700 transition">
                                    <x-lucide-edit class="w-4 h-4 mr-1" />
                                    Edit
                                </a>
                                <a href="{{ route('penilaian.print', $magang->penilaianAkhir->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                                    <x-lucide-printer class="w-4 h-4 mr-1" />
                                    Print
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Nilai Komponen -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Keputusan Pemberi</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_keputusan_pemberi ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Disiplin</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_disiplin ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Prioritas</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_prioritas ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Tepat Waktu</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_tepat_waktu ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Bekerja Sama</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_bekerja_sama ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Bekerja Mandiri</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_bekerja_mandiri ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Ketelitian</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_ketelitian ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Belajar & Menyerap</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_belajar_menyerap ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Analisa & Merancang</label>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($magang->penilaianAkhir->nilai_analisa_merancang ?? 0, 2) }}</p>
                    </div>
                </div>

                <!-- Jumlah Total -->
                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700 uppercase">Total Nilai (9 Komponen)</span>
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($magang->penilaianAkhir->jumlah_nilai ?? 0, 2) }}</span>
                    </div>
                </div>

                <!-- Umpan Balik & Informasi Tambahan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <label class="text-sm font-medium text-gray-700 flex items-center mb-2">
                            <x-lucide-message-square class="w-4 h-4 mr-2 text-yellow-600" />
                            Umpan Balik Pembimbing
                        </label>
                        <p class="text-gray-900">{{ $magang->penilaianAkhir->umpan_balik ?? 'Tidak ada umpan balik' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Penilaian</label>
                        <p class="text-gray-900 mt-1">
                            {{ $magang->penilaianAkhir->tanggal_penilaian ? \Carbon\Carbon::parse($magang->penilaianAkhir->tanggal_penilaian)->format('d F Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600">Dinilai Oleh</label>
                        <p class="text-gray-900 mt-1">
                            {{ $magang->penilaianAkhir->penilai->name ?? ($magang->pembimbing->name ?? '-') }}
                        </p>
                    </div>

                    @if ($magang->penilaianAkhir->path_surat_nilai)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 mb-2 block">Surat Nilai</label>
                            <a href="{{ Storage::url($magang->penilaianAkhir->path_surat_nilai) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                                <x-lucide-file-text class="w-5 h-5 mr-2" />
                                Download Surat Nilai
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada penilaian akhir</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('magang.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                Kembali
            </a>

            @if (Auth::user()->role === 'hr')
                <form action="{{ route('magang.destroy', $magang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data magang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <x-lucide-trash-2 class="w-5 h-5 mr-2" />
                        Hapus Data
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-admin-layouts>
