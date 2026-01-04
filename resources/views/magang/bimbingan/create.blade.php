<x-admin-layouts>
    <x-slot name="header">
        Tambah Log Bimbingan
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @include('magang.bimbingan.form', [
            'logBimbingan' => null,
            'action' => route('bimbingan.store'),
            'method' => 'POST',
            'magangs' => $dataMagang ?? [],
        ])
    </div>
</x-admin-layouts>
