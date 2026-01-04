<x-admin-layouts>
    <x-slot name="header">
        Log Bimbingan
    </x-slot>
    <div class="w-full md:w-11/12 xl:w-10/12 mx-auto mt-8 p-4 md:p-8 bg-white rounded shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
            <h2 class="text-lg font-semibold text-blue-900">Log Bimbingan</h2>
            @if (Auth::user()->role !== 'magang')
                <a href="{{ route('bimbingan.create') }}" class="px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-800">Tambah Log Bimbingan</a>
            @endif
        </div>
        <x-admin.table>
            <x-slot name="thead">
                <tr>
                    <th class="px-4 py-2 border">Waktu Bimbingan</th>
                    <th class="px-4 py-2 border">Catatan Peserta</th>
                    <th class="px-4 py-2 border">Catatan Pembimbing</th>
                </tr>
            </x-slot>
            @forelse($log as $l)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-2 border">{{ $l->waktu_bimbingan }}</td>
                    <td class="px-4 py-2 border">{{ $l->catatan_peserta }}</td>
                    <td class="px-4 py-2 border">{{ $l->catatan_pembimbing }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-2 border text-center text-gray-500">Belum ada log bimbingan</td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>
</x-admin-layouts>
