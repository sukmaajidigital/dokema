@props(['penilaian' => null, 'action', 'method' => 'POST', 'magangs' => []])

<div class="bg-white rounded-lg shadow">
    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                {{ $penilaian ? 'Edit Penilaian Akhir' : 'Tambah Penilaian Akhir Baru' }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ $penilaian ? 'Perbarui informasi penilaian akhir' : 'Lengkapi formulir untuk menambah penilaian akhir baru' }}
            </p>
        </div>

        <div class="px-6 py-4 space-y-6">
            <!-- Data Magang Selection -->
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Magang</h4>
                <x-admin.form-select name="data_magang_id" label="Pilih Data Magang" required="true" placeholder="Pilih data magang">
                    @foreach ($magangs as $magang)
                        <option value="{{ $magang->id }}" {{ old('data_magang_id', $penilaian->data_magang_id ?? '') == $magang->id ? 'selected' : '' }}>
                            {{ $magang->profilPeserta->user->name ?? 'N/A' }} -
                            {{ $magang->profilPeserta->nim }}
                            ({{ $magang->profilPeserta->universitas }})
                        </option>
                    @endforeach
                </x-admin.form-select>
            </div>

            <!-- Assessment Information -->
            <div class="bg-green-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Penilaian</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.form-input name="tanggal_penilaian" label="Tanggal Penilaian" type="date" :value="old('tanggal_penilaian', $penilaian->tanggal_penilaian ?? '')" required="true" />

                    <x-admin.form-input name="penilai" label="Nama Penilai" type="text" :value="old('penilai', $penilaian->penilai ?? '')" placeholder="Nama pembimbing atau penilai" required="true" />
                </div>
            </div>

            <!-- Assessment Scores -->
            <div class="bg-yellow-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Aspek Penilaian</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.form-select name="nilai_kedisiplinan" label="Nilai Kedisiplinan (1-100)" required="true" placeholder="Pilih nilai kedisiplinan">
                        @for ($i = 100; $i >= 60; $i -= 5)
                            <option value="{{ $i }}" {{ old('nilai_kedisiplinan', $penilaian->nilai_kedisiplinan ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }} -
                                @if ($i >= 85)
                                    Sangat Baik
                                @elseif($i >= 75)
                                    Baik
                                @elseif($i >= 65)
                                    Cukup
                                @else
                                    Kurang
                                @endif
                            </option>
                        @endfor
                    </x-admin.form-select>

                    <x-admin.form-select name="nilai_keterampilan" label="Nilai Keterampilan (1-100)" required="true" placeholder="Pilih nilai keterampilan">
                        @for ($i = 100; $i >= 60; $i -= 5)
                            <option value="{{ $i }}" {{ old('nilai_keterampilan', $penilaian->nilai_keterampilan ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }} -
                                @if ($i >= 85)
                                    Sangat Baik
                                @elseif($i >= 75)
                                    Baik
                                @elseif($i >= 65)
                                    Cukup
                                @else
                                    Kurang
                                @endif
                            </option>
                        @endfor
                    </x-admin.form-select>

                    <x-admin.form-select name="nilai_komunikasi" label="Nilai Komunikasi (1-100)" required="true" placeholder="Pilih nilai komunikasi">
                        @for ($i = 100; $i >= 60; $i -= 5)
                            <option value="{{ $i }}" {{ old('nilai_komunikasi', $penilaian->nilai_komunikasi ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }} -
                                @if ($i >= 85)
                                    Sangat Baik
                                @elseif($i >= 75)
                                    Baik
                                @elseif($i >= 65)
                                    Cukup
                                @else
                                    Kurang
                                @endif
                            </option>
                        @endfor
                    </x-admin.form-select>

                    <x-admin.form-select name="nilai_inisiatif" label="Nilai Inisiatif (1-100)" required="true" placeholder="Pilih nilai inisiatif">
                        @for ($i = 100; $i >= 60; $i -= 5)
                            <option value="{{ $i }}" {{ old('nilai_inisiatif', $penilaian->nilai_inisiatif ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }} -
                                @if ($i >= 85)
                                    Sangat Baik
                                @elseif($i >= 75)
                                    Baik
                                @elseif($i >= 65)
                                    Cukup
                                @else
                                    Kurang
                                @endif
                            </option>
                        @endfor
                    </x-admin.form-select>

                    <x-admin.form-select name="nilai_kerjasama" label="Nilai Kerjasama (1-100)" required="true" placeholder="Pilih nilai kerjasama">
                        @for ($i = 100; $i >= 60; $i -= 5)
                            <option value="{{ $i }}" {{ old('nilai_kerjasama', $penilaian->nilai_kerjasama ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }} -
                                @if ($i >= 85)
                                    Sangat Baik
                                @elseif($i >= 75)
                                    Baik
                                @elseif($i >= 65)
                                    Cukup
                                @else
                                    Kurang
                                @endif
                            </option>
                        @endfor
                    </x-admin.form-select>
                </div>

                @if ($penilaian)
                    <!-- Nilai Akhir Display -->
                    <div class="mt-6 p-4 bg-white rounded-lg border">
                        <div class="text-center">
                            <span class="text-sm text-gray-500">Nilai Akhir:</span>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ number_format(($penilaian->nilai_kedisiplinan + $penilaian->nilai_keterampilan + $penilaian->nilai_komunikasi + $penilaian->nilai_inisiatif + $penilaian->nilai_kerjasama) / 5, 1) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                @php
                                    $average = ($penilaian->nilai_kedisiplinan + $penilaian->nilai_keterampilan + $penilaian->nilai_komunikasi + $penilaian->nilai_inisiatif + $penilaian->nilai_kerjasama) / 5;
                                @endphp
                                @if ($average >= 85)
                                    <span class="text-green-600 font-medium">A - Sangat Baik</span>
                                @elseif($average >= 75)
                                    <span class="text-blue-600 font-medium">B - Baik</span>
                                @elseif($average >= 65)
                                    <span class="text-yellow-600 font-medium">C - Cukup</span>
                                @else
                                    <span class="text-red-600 font-medium">D - Kurang</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Comments and Feedback -->
            <div class="bg-purple-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Komentar dan Saran</h4>
                <div class="space-y-6">
                    <x-admin.form-textarea name="komentar" label="Komentar Umum" :value="old('komentar', $penilaian->komentar ?? '')" placeholder="Berikan komentar umum tentang kinerja peserta magang..." rows="4" required="true" />

                    <x-admin.form-textarea name="saran_perbaikan" label="Saran Perbaikan" :value="old('saran_perbaikan', $penilaian->saran_perbaikan ?? '')" placeholder="Berikan saran untuk perbaikan di masa mendatang..." rows="3" />

                    <x-admin.form-textarea name="kelebihan" label="Kelebihan Peserta" :value="old('kelebihan', $penilaian->kelebihan ?? '')" placeholder="Sebutkan kelebihan atau poin positif dari peserta..." rows="3" />
                </div>
            </div>

            <!-- Document Upload -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Dokumen Penilaian</h4>
                <div x-data="enhancedFilePreview()" class="space-y-3">
                    <x-input-label for="path_sertifikat" value="Sertifikat atau Dokumen Penilaian (PDF/Gambar)" />
                    <div class="relative">
                        <input type="file" name="path_sertifikat" id="path_sertifikat" @change="handleFileSelect($event)" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <!-- Enhanced Preview Area -->
                    <div x-show="fileUrl || existingFile" class="mt-3">
                        <!-- New File Preview -->
                        <template x-if="fileUrl">
                            <div class="border rounded-lg p-3 bg-white cursor-pointer hover:shadow-md transition-shadow" @click="openModal()">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Preview File Baru (Klik untuk perbesar):</span>
                                    <button type="button" @click.stop="clearFile()" class="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded hover:bg-red-50">
                                        Hapus
                                    </button>
                                </div>
                                <template x-if="fileType === 'image'">
                                    <div class="relative">
                                        <img :src="fileUrl" class="max-h-32 w-full object-contain rounded border hover:opacity-80 transition-opacity" alt="Preview">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-black bg-opacity-30 rounded transition-opacity">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="fileType === 'pdf'">
                                    <div class="flex items-center space-x-2 p-3 bg-red-50 rounded hover:bg-red-100 transition-colors">
                                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                        </svg>
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-700 font-medium" x-text="fileName"></span>
                                            <p class="text-xs text-gray-500">PDF - Klik untuk preview</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Existing File -->
                        @if (isset($penilaian) && $penilaian && $penilaian->path_sertifikat)
                            <template x-if="!fileUrl">
                                <div class="border rounded-lg p-3 bg-blue-50 cursor-pointer hover:shadow-md transition-shadow" @click="openExistingFile('{{ asset('storage/' . $penilaian->path_sertifikat) }}', '{{ pathinfo($penilaian->path_sertifikat, PATHINFO_EXTENSION) }}')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                                            </svg>
                                            <div>
                                                <span class="text-sm font-medium text-gray-700">Sertifikat/Dokumen saat ini</span>
                                                <p class="text-xs text-gray-500">Klik untuk preview</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </div>
                            </template>
                        @endif
                    </div>

                    @error('path_sertifikat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @error('path_sertifikat')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
</div>

<!-- Status -->
<div>
    <x-admin.form-select name="status_penilaian" label="Status Penilaian" required="true" placeholder="Pilih status penilaian">
        <option value="draft" {{ old('status_penilaian', $penilaian->status_penilaian ?? 'draft') === 'draft' ? 'selected' : '' }}>
            Draft
        </option>
        <option value="completed" {{ old('status_penilaian', $penilaian->status_penilaian ?? '') === 'completed' ? 'selected' : '' }}>
            Selesai
        </option>
        <option value="approved" {{ old('status_penilaian', $penilaian->status_penilaian ?? '') === 'approved' ? 'selected' : '' }}>
            Disetujui
        </option>
    </x-admin.form-select>
</div>

@if ($penilaian)
    <!-- Status Information -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Penilaian</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Dibuat:</span>
                <span class="font-medium">{{ $penilaian->created_at->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="text-gray-500">Terakhir update:</span>
                <span class="font-medium">{{ $penilaian->updated_at->format('d M Y H:i') }}</span>
            </div>
            @if ($penilaian->datamagang)
                <div>
                    <span class="text-gray-500">Peserta:</span>
                    <span class="font-medium">{{ $penilaian->datamagang->profilPeserta->user->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Periode Magang:</span>
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($penilaian->datamagang->tanggal_mulai)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($penilaian->datamagang->tanggal_selesai)->format('d M Y') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
@endif
</div>

<div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
    <a href="{{ route('penilaian.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Batal
    </a>
    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ $penilaian ? 'Update Penilaian' : 'Simpan Penilaian' }}
    </button>
</div>
</form>
</div>

<!-- Modal Preview Global -->
<div x-data="globalModal()" @open-modal.window="openModal($event.detail)" @keydown.escape.window="closeModal()" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

    <!-- Background Overlay -->
    <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()">
    </div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-4xl max-h-[90vh] overflow-hidden">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Preview Sertifikat: <span x-text="content.name" class="text-sm font-normal text-gray-600"></span>
                </h3>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="max-h-[70vh] overflow-auto bg-gray-50">
                <template x-if="content.type === 'image'">
                    <div class="p-4 flex items-center justify-center">
                        <img :src="content.url" :alt="content.name" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                    </div>
                </template>

                <template x-if="content.type === 'pdf'">
                    <div class="h-[70vh] w-full">
                        <iframe :src="content.url" class="w-full h-full border-0" frameborder="0">
                        </iframe>
                    </div>
                </template>

                <template x-if="content.type === 'other'">
                    <div class="p-8 text-center">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 18h12V6l-4-4H4v16zm8-14v4h4l-4-4z" />
                        </svg>
                        <p class="text-gray-600 mb-4">File tidak dapat di-preview di browser</p>
                        <a :href="content.url" download class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Sertifikat
                        </a>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between">
                <template x-if="content.type !== 'other'">
                    <a :href="content.url" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Buka di Tab Baru
                    </a>
                </template>
                <div class="ml-auto">
                    <button type="button" @click="closeModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function enhancedFilePreview() {
        return {
            fileUrl: null,
            fileName: '',
            fileType: '',
            existingFile: false,

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.fileUrl = URL.createObjectURL(file);

                    if (file.type.startsWith('image/')) {
                        this.fileType = 'image';
                    } else if (file.type === 'application/pdf') {
                        this.fileType = 'pdf';
                    } else {
                        this.fileType = 'other';
                    }
                }
            },

            clearFile() {
                this.fileUrl = null;
                this.fileName = '';
                this.fileType = '';
                // Reset the file input
                const input = this.$el.querySelector('input[type="file"]');
                if (input) input.value = '';
            },

            openModal() {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: {
                        url: this.fileUrl,
                        type: this.fileType,
                        name: this.fileName
                    }
                }));
            },

            openExistingFile(url, extension) {
                let fileType = 'other';
                if (['jpg', 'jpeg', 'png', 'gif'].includes(extension.toLowerCase())) {
                    fileType = 'image';
                } else if (extension.toLowerCase() === 'pdf') {
                    fileType = 'pdf';
                }

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: {
                        url: url,
                        type: fileType,
                        name: 'Sertifikat saat ini.' + extension
                    }
                }));
            }
        }
    }

    function globalModal() {
        return {
            isOpen: false,
            content: {
                url: '',
                type: '',
                name: ''
            },

            openModal(details) {
                this.content = details;
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isOpen = false;
                document.body.style.overflow = 'auto';
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
