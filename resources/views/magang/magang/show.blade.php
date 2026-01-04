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
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Diterima</span>
                        @elseif($magang->status === 'ditolak')
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Ditolak</span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Menunggu</span>
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
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Dokumen</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Surat Permohonan</label>
                    @if ($magang->path_surat_permohonan)
                        <a href="{{ Storage::url($magang->path_surat_permohonan) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 mt-1">
                            <x-lucide-file-text class="w-5 h-5 mr-2" />
                            Lihat Dokumen
                        </a>
                    @else
                        <p class="text-gray-500 mt-1">Belum diunggah</p>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Surat Balasan</label>
                    @if ($magang->path_surat_balasan)
                        <a href="{{ Storage::url($magang->path_surat_balasan) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 mt-1">
                            <x-lucide-file-text class="w-5 h-5 mr-2" />
                            Lihat Dokumen
                        </a>
                    @else
                        <p class="text-gray-500 mt-1">Belum diunggah</p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nilai Kehadiran</label>
                        <p class="text-gray-900 mt-1 text-2xl font-semibold">{{ $magang->penilaianAkhir->nilai_kehadiran ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nilai Kedisiplinan</label>
                        <p class="text-gray-900 mt-1 text-2xl font-semibold">{{ $magang->penilaianAkhir->nilai_kedisiplinan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nilai Keterampilan</label>
                        <p class="text-gray-900 mt-1 text-2xl font-semibold">{{ $magang->penilaianAkhir->nilai_keterampilan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nilai Sikap</label>
                        <p class="text-gray-900 mt-1 text-2xl font-semibold">{{ $magang->penilaianAkhir->nilai_sikap ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-600">Nilai Rata-rata</label>
                        <p class="text-gray-900 mt-1 text-3xl font-bold text-blue-600">
                            {{ number_format($magang->penilaianAkhir->nilai_rata_rata ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-600">Umpan Balik</label>
                        <p class="text-gray-900 mt-1">{{ $magang->penilaianAkhir->umpan_balik ?? '-' }}</p>
                    </div>
                    @if ($magang->penilaianAkhir->path_surat_nilai)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-600">Surat Nilai</label>
                            <a href="{{ Storage::url($magang->penilaianAkhir->path_surat_nilai) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 mt-1">
                                <x-lucide-file-text class="w-5 h-5 mr-2" />
                                Download Surat Nilai
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->role !== 'magang')
                        <div class="md:col-span-2">
                            <a href="{{ route('penilaian.edit', $magang->penilaianAkhir->id) }}" class="text-blue-600 hover:text-blue-800">
                                Edit Penilaian
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
