<x-admin-layouts>
    <x-slot name="header">
        Tambah Laporan Kegiatan
    </x-slot>
    <div class="w-full md:w-7/12 xl:w-5/12 mx-auto mt-8 p-4 md:p-8 bg-white rounded shadow-md">
        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script>
            function fileUploadLampiran() {
                return {
                    file: null,
                    fileUrl: '',
                    handleFileChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.file = file;
                            this.fileUrl = URL.createObjectURL(file);
                        }
                    },
                    handleDrop(e) {
                        const file = e.dataTransfer.files[0];
                        if (file) {
                            this.file = file;
                            this.fileUrl = URL.createObjectURL(file);
                        }
                    }
                }
            }
        </script>
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="data_magang_id" class="block font-semibold mb-2">Pilih Data Magang</label>
                <select name="data_magang_id" id="data_magang_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Data Magang --</option>
                    @foreach ($dataMagangList as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->id }} - {{ $dm->profilPeserta->nama ?? 'Peserta' }} ({{ $dm->tanggal_mulai }} - {{ $dm->tanggal_selesai }})</option>
                    @endforeach
                </select>
            </div>
            <x-admin.form-input name="tanggal_laporan" label="Tanggal Laporan" type="date" required="true" />
            <x-admin.form-textarea name="deskripsi" label="Deskripsi" required="true" />
            <div x-data="fileUploadLampiran()" class="mb-4">
                <label class="block font-semibold mb-2">Lampiran (PDF/Gambar)</label>
                <div class="border-2 border-dashed border-gray-400 rounded p-6 flex flex-col items-center justify-center cursor-pointer bg-gray-50 hover:bg-gray-100 transition" @dragover.prevent @drop.prevent="handleDrop($event)" @click="$refs.lampiran.click()">
                    <input type="file" name="lampiran" accept="application/pdf,image/*" class="hidden" x-ref="lampiran" @change="handleFileChange($event)">
                    <template x-if="file">
                        <div class="mb-2 w-full flex flex-col items-center">
                            <template x-if="file.type && file.type.startsWith('image/')">
                                <img :src="fileUrl" class="max-h-40 mb-2 rounded shadow" />
                            </template>
                            <template x-if="file.type === 'application/pdf'">
                                <iframe :src="fileUrl" class="w-full h-40 mb-2 border rounded"></iframe>
                            </template>
                            <div class="text-sm font-medium text-blue-700" x-text="file.name"></div>
                        </div>
                    </template>
                    <template x-if="!file">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4m5 5l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            <span class="font-semibold">Klik atau drag file ke sini</span>
                            <span class="text-xs">Format: PDF, JPG, PNG</span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <x-admin.form-button>Simpan</x-admin.form-button>
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layouts>
