<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menunggu Persetujuan - DOKEMA</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/telkom_logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <img class="mx-auto h-24 w-auto" src="{{ asset('logo/telkom_logo.png') }}" alt="DOKEMA">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Menunggu Persetujuan
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Pendaftaran Anda sedang di-review oleh HRD
                </p>
            </div>

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ $errors->first() }}
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Card -->
            <div class="mt-8 space-y-6">
                <div class="rounded-lg bg-white shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                Status Pendaftaran
                            </h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-semibold text-gray-900">{{ $user->email }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-semibold text-gray-900">{{ $user->profilPeserta->nama ?? 'N/A' }}</span>
                                </div>
                                @if ($dataMagang)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Status Permohonan:</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if ($dataMagang->workflow_status === 'approved') bg-green-100 text-green-800
                                            @elseif ($dataMagang->workflow_status === 'rejected')
                                                bg-red-100 text-red-800
                                            @else
                                                bg-yellow-100 text-yellow-800 @endif
                                        ">
                                            @if ($dataMagang->workflow_status === 'submitted' || $dataMagang->workflow_status === 'under_review')
                                                Menunggu Review
                                            @elseif ($dataMagang->workflow_status === 'approved')
                                                Disetujui
                                            @elseif ($dataMagang->workflow_status === 'rejected')
                                                Ditolak
                                            @else
                                                {{ $dataMagang->workflow_status }}
                                            @endif
                                        </span>
                                    </div>
                                    @if ($dataMagang->workflow_status === 'rejected' && $dataMagang->catatan_penolakan)
                                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded">
                                            <p class="text-sm text-red-700">
                                                <strong>Alasan Penolakan:</strong><br>
                                                {{ $dataMagang->catatan_penolakan }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Messages -->
                @if ($dataMagang && $dataMagang->workflow_status !== 'rejected')
                    <div class="rounded-md bg-blue-50 p-4 border border-blue-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Pendaftaran Anda sedang di-review oleh HRD Grapari Kudus Telkom Akses.
                                    Mohon tunggu untuk persetujuan. Anda akan menerima email notifikasi saat
                                    permohonan disetujui atau ditolak.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-md bg-green-50 p-4 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Catatan:</strong> Anda hanya dapat login setelah permohonan Anda disetujui oleh HRD.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif ($dataMagang && $dataMagang->workflow_status === 'rejected')
                    <div class="rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Mohon maaf,</strong> permohonan Anda telah ditolak oleh HRD. Silakan hubungi
                                    HRD untuk informasi lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contact Info -->
                <div class="rounded-lg bg-gray-50 p-4 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-3">Kontak HRD Grapari Kudus</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Email:</strong> hrd@grapari-kudus.telkom.co.id</p>
                        <p><strong>Telepon:</strong> +62 (0) 291 123 4567</p>
                        <p><strong>Jam Kerja:</strong> Senin - Jumat, 08:00 - 16:30 WIB</p>
                    </div>
                </div>

                <!-- Refresh Button -->
                <div class="text-center">
                    <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 1119.414 0V3a1 1 0 11-2 0v2.101A5.002 5.002 0 005.999 5H4a1 1 0 01-1-1zm.008 9a1 1 0 011.992 0A9.009 9.009 0 0110 19.9a9.009 9.009 0 014-17.799 1 1 0 111.992 0A11.009 11.009 0 1010 21.91a11.009 11.009 0 01-5.992-9.91z" clip-rule="evenodd" />
                        </svg>
                        Refresh Status
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                            Kembali ke Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
