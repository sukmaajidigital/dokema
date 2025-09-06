<x-admin-layouts>
    <x-slot name="header">
        Dashboard
    </x-slot>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Peserta -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalPeserta }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Magang -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Magang</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalMagang }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Magang Aktif -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Magang Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $magangAktif }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Laporan -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalLaporan }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Magang Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Magang</h3>
                <div class="space-y-3">
                    @php
                        $colors = ['diterima' => 'green', 'menunggu' => 'yellow', 'ditolak' => 'red'];
                        $totalStatus = array_sum($statusMagang);
                    @endphp
                    @foreach ($statusMagang as $status => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-{{ $colors[$status] ?? 'gray' }}-500 mr-2"></div>
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">{{ $count }}</span>
                                <span class="text-xs text-gray-500">({{ $totalStatus > 0 ? round(($count / $totalStatus) * 100, 1) : 0 }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $colors[$status] ?? 'gray' }}-500 h-2 rounded-full" style="width: {{ $totalStatus > 0 ? ($count / $totalStatus) * 100 : 0 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Magang Akan Berakhir -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Magang Akan Berakhir</h3>
                <div class="space-y-3">
                    @forelse($magangAkanBerakhir as $magang)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $magang->profilPeserta->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Program Magang</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-red-600">
                                    {{ $magang->tanggal_selesai ? \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d M Y') : 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $magang->tanggal_selesai ? \Carbon\Carbon::parse($magang->tanggal_selesai)->diffForHumans() : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">Tidak ada magang yang akan berakhir dalam 30 hari ke depan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Laporan Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Laporan Terbaru</h3>
                    <a href="{{ route('laporan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($laporanTerbaru as $laporan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $laporan->dataMagang->profilPeserta->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($laporan->deskripsi ?? 'Laporan kegiatan', 50) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">Belum ada laporan yang dibuat</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Bimbingan Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Bimbingan Terbaru</h3>
                    <a href="{{ route('bimbingan.index', 1) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($bimbinganTerbaru as $bimbingan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $bimbingan->dataMagang->profilPeserta->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($bimbingan->catatan_peserta ?? ($bimbingan->catatan_pembimbing ?? 'Catatan bimbingan'), 50) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $bimbingan->waktu_bimbingan ? \Carbon\Carbon::parse($bimbingan->waktu_bimbingan)->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">Belum ada bimbingan yang dilakukan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('profil.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-700">Tambah Peserta</span>
                </a>

                <a href="{{ route('magang.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-700">Tambah Magang</span>
                </a>

                <a href="{{ route('laporan.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-700">Buat Laporan</span>
                </a>

                <a href="{{ route('user.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-700">Tambah User</span>
                </a>

                <a href="{{ route('profil.index') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-sm font-medium text-indigo-700">Kelola Profil</span>
                </a>

                <a href="{{ route('magang.index') }}" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                    <svg class="w-8 h-8 text-pink-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <span class="text-sm font-medium text-pink-700">Kelola Magang</span>
                </a>
            </div>
        </div>
    </div>
</x-admin-layouts>
