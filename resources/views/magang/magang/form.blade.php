@props(['magang' => null, 'action', 'method' => 'POST', 'profils' => []])

<div class="bg-white rounded-lg shadow">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" x-data="documentPreview()">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                {{ $magang ? 'Edit Data Magang' : 'Tambah Data Magang Baru' }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ $magang ? 'Perbarui informasi data magang' : 'Lengkapi formulir untuk menambah data magang baru' }}
            </p>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Data Peserta & Periode -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Data Peserta Magang
                    </h4>

                    @if ($magang)
                        <!-- Edit Mode: Display Only -->
                        <div class="bg-blue-50 rounded-lg p-4 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 uppercase">Nama Peserta</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->nama_peserta ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 uppercase">Email</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->user->email ?? '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 uppercase">NIM/NISN</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->nim ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 uppercase">No. HP</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->no_hp ?? '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 uppercase">Universitas</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->universitas ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 uppercase">Jurusan</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $magang->profilPeserta->jurusan ?? '-' }}</p>
                            </div>
                            <input type="hidden" name="profil_peserta_id" value="{{ $magang->profil_peserta_id }}">
                            <p class="text-xs text-gray-500 italic pt-2 border-t">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Data peserta tidak dapat diubah saat edit
                            </p>
                        </div>
                    @else
                        <!-- Create Mode: Select Peserta -->
                        <div>
                            <label for="profil_peserta_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Pilih Peserta
                            </label>
                            <select id="profil_peserta_id" name="profil_peserta_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($profils as $profil)
                                    <option value="{{ $profil->id }}" {{ old('profil_peserta_id') == $profil->id ? 'selected' : '' }}>
                                        {{ $profil->nama_peserta ?? ($profil->user->name ?? 'N/A') }} - {{ $profil->nim }} ({{ $profil->universitas }})
                                    </option>
                                @endforeach
                            </select>
                            @error('profil_peserta_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Pilih peserta yang sudah terdaftar di sistem</p>
                        </div>
                    @endif

                    <!-- Periode Magang -->
                    <div class="pt-2">
                        <h5 class="text-sm font-semibold text-gray-900 mb-3">Periode Magang</h5>
                        <div class="space-y-3">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Mulai <span class="text-red-600">*</span>
                                </label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('tanggal_mulai', $magang->tanggal_mulai ?? '') }}">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Selesai <span class="text-red-600">*</span>
                                </label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('tanggal_selesai', $magang->tanggal_selesai ?? '') }}">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-600">*</span>
                        </label>
                        <select id="status" name="status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="menunggu" {{ old('status', $magang->status ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diterima" {{ old('status', $magang->status ?? '') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ old('status', $magang->status ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ old('status', $magang->status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pembimbing (Only for HR) -->
                    @if (Auth::user()->role === 'hr')
                        <div>
                            <label for="pembimbing_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Pembimbing <span class="text-gray-500">(Opsional)</span>
                            </label>
                            <select id="pembimbing_id" name="pembimbing_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">-- Belum Ditentukan --</option>
                                @foreach (\App\Models\User::where('role', 'pembimbing')->get() as $pembimbing)
                                    <option value="{{ $pembimbing->id }}" {{ old('pembimbing_id', $magang->pembimbing_id ?? '') == $pembimbing->id ? 'selected' : '' }}>
                                        {{ $pembimbing->name }} ({{ $pembimbing->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pembimbing_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif($magang && $magang->pembimbing)
                        <div class="bg-purple-50 rounded-lg p-4">
                            <label class="block text-xs font-medium text-gray-600 uppercase mb-2">Pembimbing</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $magang->pembimbing->name }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $magang->pembimbing->email }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Dokumen -->
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Dokumen Magang
                    </h4>

                    <p class="text-xs text-gray-500 bg-yellow-50 p-2 rounded">
                        <strong>Format:</strong> PDF, JPG, PNG | <strong>Maksimal:</strong> 2MB per file
                    </p>

                    <!-- Surat Permohonan -->
                    <div>
                        <label for="path_surat_permohonan" class="block text-sm font-medium text-gray-700 mb-1">
                            Surat Permohonan {{ $magang ? '' : '<span class="text-red-600">*</span>' }}
                        </label>
                        <input type="file" id="path_surat_permohonan" name="path_surat_permohonan" {{ $magang ? '' : 'required' }} accept=".pdf,.jpg,.jpeg,.png" @change="previewFile($event, 'permohonan')" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('path_surat_permohonan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview Surat Permohonan -->
                        @if ($magang && $magang->path_surat_permohonan)
                            <div class="mt-3 border rounded-lg p-3 bg-blue-50">
                                <p class="text-xs font-medium text-gray-700 mb-2">File Saat Ini:</p>
                                @php
                                    $ext = strtolower(pathinfo($magang->path_surat_permohonan, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ asset('storage/' . $magang->path_surat_permohonan) }}" alt="Surat Permohonan" class="w-full h-32 object-contain rounded bg-white mb-2">
                                @else
                                    <div class="flex items-center p-3 bg-white rounded">
                                        <svg class="w-8 h-8 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ basename($magang->path_surat_permohonan) }}</span>
                                    </div>
                                @endif
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ asset('storage/' . $magang->path_surat_permohonan) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">Lihat</a>
                                    <a href="{{ asset('storage/' . $magang->path_surat_permohonan) }}" download class="text-xs text-green-600 hover:text-green-800">Download</a>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 italic">Upload file baru untuk mengganti</p>
                            </div>
                        @endif

                        <!-- New File Preview -->
                        <div x-show="previews.permohonan.url" x-cloak class="mt-3 border rounded-lg p-3 bg-green-50">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs font-medium text-gray-700">Preview File Baru:</p>
                                <button type="button" @click="clearPreview('permohonan')" class="text-xs text-red-600 hover:text-red-800">Hapus</button>
                            </div>
                            <template x-if="previews.permohonan.type === 'image'">
                                <img :src="previews.permohonan.url" class="w-full h-32 object-contain rounded bg-white">
                            </template>
                            <template x-if="previews.permohonan.type === 'pdf'">
                                <div class="flex items-center p-3 bg-white rounded">
                                    <svg class="w-8 h-8 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                    </svg>
                                    <span class="text-sm text-gray-700" x-text="previews.permohonan.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Surat Balasan -->
                    <div>
                        <label for="path_surat_balasan" class="block text-sm font-medium text-gray-700 mb-1">
                            Surat Balasan <span class="text-gray-500">(Opsional)</span>
                        </label>
                        <input type="file" id="path_surat_balasan" name="path_surat_balasan" accept=".pdf,.jpg,.jpeg,.png" @change="previewFile($event, 'balasan')" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        @error('path_surat_balasan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Preview Surat Balasan -->
                        @if ($magang && $magang->path_surat_balasan)
                            <div class="mt-3 border rounded-lg p-3 bg-blue-50">
                                <p class="text-xs font-medium text-gray-700 mb-2">File Saat Ini:</p>
                                @php
                                    $ext = strtolower(pathinfo($magang->path_surat_balasan, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ asset('storage/' . $magang->path_surat_balasan) }}" alt="Surat Balasan" class="w-full h-32 object-contain rounded bg-white mb-2">
                                @else
                                    <div class="flex items-center p-3 bg-white rounded">
                                        <svg class="w-8 h-8 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ basename($magang->path_surat_balasan) }}</span>
                                    </div>
                                @endif
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ asset('storage/' . $magang->path_surat_balasan) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">Lihat</a>
                                    <a href="{{ asset('storage/' . $magang->path_surat_balasan) }}" download class="text-xs text-green-600 hover:text-green-800">Download</a>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 italic">Upload file baru untuk mengganti</p>
                            </div>
                        @endif

                        <!-- New File Preview -->
                        <div x-show="previews.balasan.url" x-cloak class="mt-3 border rounded-lg p-3 bg-green-50">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs font-medium text-gray-700">Preview File Baru:</p>
                                <button type="button" @click="clearPreview('balasan')" class="text-xs text-red-600 hover:text-red-800">Hapus</button>
                            </div>
                            <template x-if="previews.balasan.type === 'image'">
                                <img :src="previews.balasan.url" class="w-full h-32 object-contain rounded bg-white">
                            </template>
                            <template x-if="previews.balasan.type === 'pdf'">
                                <div class="flex items-center p-3 bg-white rounded">
                                    <svg class="w-8 h-8 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                    </svg>
                                    <span class="text-sm text-gray-700" x-text="previews.balasan.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Info Tambahan untuk Edit Mode -->
                    @if ($magang)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h5 class="text-xs font-semibold text-gray-900 uppercase mb-3">Informasi Sistem</h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Magang:</span>
                                    <span class="font-medium text-gray-900">#{{ str_pad($magang->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibuat:</span>
                                    <span class="font-medium text-gray-900">{{ $magang->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Update:</span>
                                    <span class="font-medium text-gray-900">{{ $magang->updated_at->format('d M Y H:i') }}</span>
                                </div>
                                @if ($magang->workflow_status)
                                    <div class="flex justify-between pt-2 border-t">
                                        <span class="text-gray-600">Workflow:</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $magang->workflow_status)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <p class="text-xs text-gray-500">
                <span class="text-red-600 font-medium">*</span> menandakan field wajib diisi
            </p>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('magang.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $magang ? 'Update Data Magang' : 'Simpan Data Magang' }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function documentPreview() {
        return {
            previews: {
                permohonan: {
                    url: null,
                    type: null,
                    name: null
                },
                balasan: {
                    url: null,
                    type: null,
                    name: null
                }
            },

            previewFile(event, type) {
                const file = event.target.files[0];
                if (!file) return;

                this.previews[type].name = file.name;
                this.previews[type].url = URL.createObjectURL(file);

                if (file.type.startsWith('image/')) {
                    this.previews[type].type = 'image';
                } else if (file.type === 'application/pdf') {
                    this.previews[type].type = 'pdf';
                } else {
                    this.previews[type].type = 'other';
                }
            },

            clearPreview(type) {
                this.previews[type] = {
                    url: null,
                    type: null,
                    name: null
                };
                const input = document.getElementById('path_surat_' + type);
                if (input) input.value = '';
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
