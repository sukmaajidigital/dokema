<x-admin-layouts>
    <x-slot name="header">
        Profil Saya
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- User Account Information -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $user->role }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Nama Lengkap</label>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->email }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Role</label>
                        <p class="text-sm font-semibold text-gray-900 mt-1 capitalize">{{ $user->role }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase">Terdaftar Sejak</label>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($profil)
            <!-- Profil Peserta Details -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Profil Peserta</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Info -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide border-b pb-2">Informasi Personal</h4>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Nama Peserta</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->nama_peserta ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">NIM</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->nim ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->email ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">No. Telepon</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->no_hp ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Academic Info -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide border-b pb-2">Informasi Akademik</h4>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Universitas/Institusi</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->universitas ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Jurusan</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->jurusan ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">Semester</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->semester ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase">IPK</label>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $profil->ipk ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($profil->alamat)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-xs font-medium text-gray-500 uppercase">Alamat</label>
                            <p class="text-sm text-gray-900 mt-2">{{ $profil->alamat }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($dataMagang)
                <!-- Data Magang Info -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Magang</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Periode Magang</label>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">
                                        {{ \Carbon\Carbon::parse($dataMagang->tanggal_mulai)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($dataMagang->tanggal_selesai)->format('d M Y') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Divisi</label>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $dataMagang->divisi ?? '-' }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Status Workflow</label>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if ($dataMagang->workflow_status === 'approved') bg-green-100 text-green-800
                                            @elseif($dataMagang->workflow_status === 'rejected') bg-red-100 text-red-800
                                            @elseif($dataMagang->workflow_status === 'submitted') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($dataMagang->workflow_status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @if ($dataMagang->pembimbing)
                                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                        <label class="text-xs font-medium text-blue-700 uppercase">Pembimbing</label>
                                        <div class="mt-2 flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-semibold text-gray-900">{{ $dataMagang->pembimbing->name }}</p>
                                                <p class="text-xs text-gray-600">{{ $dataMagang->pembimbing->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                        <p class="text-sm text-yellow-800">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pembimbing belum ditentukan
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($dataMagang->keterangan)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="text-xs font-medium text-gray-500 uppercase">Keterangan</label>
                                <p class="text-sm text-gray-900 mt-2">{{ $dataMagang->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <!-- No Profile Alert -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg shadow p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Profil Belum Lengkap</h3>
                        <p class="mt-2 text-sm text-yellow-700">
                            Anda belum memiliki profil peserta. Silakan hubungi administrator untuk melengkapi data profil Anda.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layouts>
