<x-admin-layouts>
    <x-slot name="header">
        Log Bimbingan
    </x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-medium text-gray-900 mb-2 sm:mb-0">Daftar Log Bimbingan</h2>
                <a href="{{ route('bimbingan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ Auth::user()->role === 'magang' ? 'Tambah Catatan' : 'Tambah Log Bimbingan' }}
                </a>
            </div>
        </div>

        <div class="p-6">
            @if ($log->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada log bimbingan</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if (Auth::user()->role === 'magang')
                            Pembimbing Anda akan mencatat setiap sesi bimbingan di sini.
                        @else
                            Mulai dengan membuat log bimbingan untuk peserta yang Anda bimbing.
                        @endif
                    </p>
                    @if (Auth::user()->role !== 'magang')
                        <div class="mt-6">
                            <a href="{{ route('bimbingan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Buat Log Pertama
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <x-admin.table id="bimbinganTable">
                    <x-slot name="thead">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Bimbingan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan Pembimbing</th>
                            @if (Auth::user()->role !== 'magang')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider no-sort">Aksi</th>
                            @endif
                        </tr>
                    </x-slot>
                    @foreach ($log as $l)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $l->dataMagang->profilPeserta->nama_peserta ?? ($l->dataMagang->profilPeserta->user->name ?? 'N/A') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $l->dataMagang->profilPeserta->nim ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($l->waktu_bimbingan)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $l->catatan_peserta }}">
                                    {{ $l->catatan_peserta ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $l->catatan_pembimbing }}">
                                    {{ $l->catatan_pembimbing ?? '-' }}
                                </div>
                            </td>
                            @if (Auth::user()->role !== 'magang')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('bimbingan.edit', $l->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('bimbingan.destroy', $l->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus log bimbingan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </x-admin.table>
            @endif
        </div>
    </div>
</x-admin-layouts>
