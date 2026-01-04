<x-admin-layouts>
    <x-slot name="header">
        Edit Log Bimbingan
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @include('magang.bimbingan.form', [
            'logBimbingan' => $log,
            'action' => route('bimbingan.update', $log->id),
            'method' => 'PUT',
            'magangs' => $dataMagang ?? [],
        ])
    </div>
</x-admin-layouts>
