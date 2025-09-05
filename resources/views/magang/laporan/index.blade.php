<x-admin-layouts>
    <x-slot name="header">
        Laporan Kegiatan
    </x-slot>
    <div class="w-full md:w-11/12 xl:w-10/12 mx-auto mt-8 p-4 md:p-8 bg-white rounded shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
            <h2 class="text-lg font-semibold text-blue-900">Laporan Kegiatan</h2>
            <a href="{{ route('laporan.create') }}" class="px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-800">Tambah Laporan</a>
        </div>
        <x-admin.table>
            <x-slot name="thead">
                <tr>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Deskripsi</th>
                    <th class="px-4 py-2 border">Lampiran</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Catatan</th>
                </tr>
            </x-slot>
            @forelse($laporan as $l)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-2 border">{{ $l->tanggal_laporan }}</td>
                    <td class="px-4 py-2 border">{{ $l->deskripsi }}</td>
                    <td class="px-4 py-2 border">
                        @if ($l->path_lampiran)
                            <a href="{{ asset('storage/' . $l->path_lampiran) }}" target="_blank" class="text-blue-700 underline">Lihat Lampiran</a>
                            @if (Str::endsWith($l->path_lampiran, ['.jpg', '.jpeg', '.png']))
                                <img src="{{ asset('storage/' . $l->path_lampiran) }}" class="max-h-20 mt-2" />
                            @elseif(Str::endsWith($l->path_lampiran, '.pdf'))
                                <iframe src="{{ asset('storage/' . $l->path_lampiran) }}" class="w-full h-20 mt-2"></iframe>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ ucfirst($l->status_verifikasi) }}</td>
                    <td class="px-4 py-2 border">{{ $l->catatan_verifikasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-2 border text-center text-gray-500">Belum ada laporan</td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>
</x-admin-layouts>
