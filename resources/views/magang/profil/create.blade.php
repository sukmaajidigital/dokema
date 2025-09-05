    <x-admin-layouts>
        <x-slot name="header">
            Buat Profil Peserta
        </x-slot>
        <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
            <form action="{{ route('profil.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label for="user_id" value="User" />
                    <select name="user_id" id="user_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <x-input-label for="nim" value="NIM" />
                    <x-text-input type="text" name="nim" id="nim" class="w-full" required />
                </div>
                <div class="mb-4">
                    <x-input-label for="universitas" value="Universitas" />
                    <x-text-input type="text" name="universitas" id="universitas" class="w-full" required />
                </div>
                <div class="mb-4">
                    <x-input-label for="jurusan" value="Jurusan" />
                    <x-text-input type="text" name="jurusan" id="jurusan" class="w-full" required />
                </div>
                <div class="mb-4">
                    <x-input-label for="no_telepon" value="No Telepon" />
                    <x-text-input type="text" name="no_telepon" id="no_telepon" class="w-full" required />
                </div>
                <div class="mb-4">
                    <x-input-label for="alamat" value="Alamat" />
                    <x-textarea name="alamat" id="alamat" class="w-full" />
                </div>
                <x-primary-button type="submit">Simpan</x-primary-button>
            </form>
        </div>
    </x-admin-layouts>
