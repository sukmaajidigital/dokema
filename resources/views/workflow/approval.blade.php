<x-admin-layouts>
    <x-slot name="header">
        Workflow Approval Magang
    </x-slot>

    <div class="space-y-6">
        <!-- Quota Status -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Status Kuota Magang</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded">
                    <div class="text-2xl font-bold text-blue-600">{{ $quota['current'] }}</div>
                    <div class="text-sm text-gray-600">Magang Aktif</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded">
                    <div class="text-2xl font-bold text-green-600">{{ $quota['available'] }}</div>
                    <div class="text-sm text-gray-600">Kuota Tersedia</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-gray-600">{{ $quota['max'] }}</div>
                    <div class="text-sm text-gray-600">Total Kuota</div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Permohonan Menunggu Persetujuan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Universitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($pendingApplications as $application)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $application->profilPeserta->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->profilPeserta->nim }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $application->profilPeserta->universitas }}</td>
                                <td class="px-6 py-4 text-sm">{{ $application->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ Storage::url($application->path_surat_permohonan) }}" target="_blank" class="text-blue-600 hover:underline">
                                        Lihat Surat
                                    </a>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <button onclick="approveApplication({{ $application->id }})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600" {{ $quota['is_full'] ? 'disabled' : '' }}>
                                        Setujui
                                    </button>
                                    <button onclick="rejectApplication({{ $application->id }})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Tolak
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Setujui Permohonan Magang</h3>
                <form id="approvalForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action" value="approve">

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Pilih Pembimbing</label>
                        <select name="pembimbing_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih Pembimbing --</option>
                            @foreach ($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">
                                    {{ $supervisor->name }} ({{ $supervisor->magang_dibimbing_count }} aktif)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Upload Surat Balasan</label>
                        <input type="file" name="surat_balasan" class="w-full border rounded px-3 py-2" accept=".pdf" required>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-green-500 text-white py-2 rounded hover:bg-green-600">
                            Setujui
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 text-white py-2 rounded hover:bg-gray-600">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Tolak Permohonan Magang</h3>
                <form id="rejectionForm" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="w-full border rounded px-3 py-2 h-24" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-red-500 text-white py-2 rounded hover:bg-red-600">
                            Tolak
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 text-white py-2 rounded hover:bg-gray-600">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function approveApplication(id) {
            document.getElementById('approvalForm').action = `/workflow/process/${id}`;
            document.getElementById('approvalModal').classList.remove('hidden');
        }

        function rejectApplication(id) {
            document.getElementById('rejectionForm').action = `/workflow/process/${id}`;
            document.getElementById('rejectionModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('approvalModal').classList.add('hidden');
            document.getElementById('rejectionModal').classList.add('hidden');
        }
    </script>
</x-admin-layouts>
