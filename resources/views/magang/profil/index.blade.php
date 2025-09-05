<x-admin-layouts>
    <x-slot name="header">
        Profil Peserta
    </x-slot>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">
        @if (session('success'))
            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Manajemen Profil Pemagang</h2>
            <a href="{{ route('profil.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah Profil</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Email</th>
                        <th class="px-4 py-2 border">NIM</th>
                        <th class="px-4 py-2 border">Universitas</th>
                        <th class="px-4 py-2 border">Jurusan</th>
                        <th class="px-4 py-2 border">No Telepon</th>
                        <th class="px-4 py-2 border">Alamat</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($profils as $profil)
                        <tr>
                            <td class="px-4 py-2 border">{{ $profil->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $profil->user->email ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $profil->nim }}</td>
                            <td class="px-4 py-2 border">{{ $profil->universitas }}</td>
                            <td class="px-4 py-2 border">{{ $profil->jurusan }}</td>
                            <td class="px-4 py-2 border">{{ $profil->no_telepon }}</td>
                            <td class="px-4 py-2 border">{{ $profil->alamat }}</td>
                            <td class="px-4 py-2 border">
                                <a href="{{ route('profil.edit', ['id' => $profil->id]) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</a>
                                <form action="{{ route('profil.destroy', ['id' => $profil->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus profil?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Belum ada data profil pemagang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layouts>
