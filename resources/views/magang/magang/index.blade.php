<x-admin-layouts>
    <x-slot name="header">
        Data Magang
    </x-slot>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-medium text-gray-900 mb-2 sm:mb-0">Manajemen Data Magang</h2>
                <a href="{{ route('magang.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Data Magang
                </a>
            </div>
        </div>

        <div class="p-6">
            <x-admin.table id="magangTable">
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider no-sort">Aksi</th>
                    </tr>
                </x-slot>
                @forelse($magang as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ substr($m->profilPeserta->user->name ?? 'N', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $m->profilPeserta->user->name ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $m->profilPeserta->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $m->pembimbing ? $m->pembimbing->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($m->tanggal_mulai)->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($m->tanggal_selesai)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if ($m->status === 'diterima') bg-green-100 text-green-800
                                @elseif($m->status === 'selesai') bg-blue-100 text-blue-800
                                @elseif($m->status === 'menunggu') bg-yellow-100 text-yellow-800
                                @elseif($m->status === 'ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('magang.show', $m->id) }}" class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded hover:bg-indigo-200" title="Lihat Detail">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('laporan.index', $m->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded" title="Laporan">
                                    Laporan
                                </a>
                                <a href="{{ route('bimbingan.index', $m->id) }}" class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded" title="Bimbingan">
                                    Bimbingan
                                </a>
                                <a href="{{ route('penilaian.index', $m->id) }}" class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded" title="Penilaian">
                                    Penilaian
                                </a>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('magang.edit', $m->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('magang.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data magang ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data magang.</td>
                    </tr>
                @endforelse
            </x-admin.table>
        </div>
    </div>
</x-admin-layouts>
