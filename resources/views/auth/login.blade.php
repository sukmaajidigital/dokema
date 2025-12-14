<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - DOKEMA</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img class="mx-auto h-24 w-auto" src="{{ asset('logo/logo.png') }}" alt="DOKEMA">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sistem Manajemen Magang
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Silakan login untuk melanjutkan
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ $errors->first() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="rounded-md bg-green-50 p-4">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                @endif

                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Email address" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 text-center">Default Login Credentials</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Password</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- HR -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">HR</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Admin HR</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">admin@dokema.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <!-- Pembimbing -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pembimbing</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Dr. Budi Santoso</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">budi.santoso@dokema.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pembimbing</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Ir. Sari Wulandari</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">sari.wulandari@dokema.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pembimbing</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Ahmad Rizki, S.T.</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">ahmad.rizki@dokema.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <!-- Magang -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Andi Pratama</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">andi.pratama@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Siti Nurhaliza</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">siti.nurhaliza@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Reza Maulana</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">reza.maulana@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Dewi Lestari</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">dewi.lestari@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Fajar Nugroho</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">fajar.nugroho@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Maya Sari</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">maya.sari@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Dimas Aditya</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">dimas.aditya@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Putri Amelia</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">putri.amelia@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Ryan Kurniawan</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">ryan.kurniawan@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Magang</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-900">Indah Permata</td>
                                <td class="px-3 py-2 whitespace-nowrap text-gray-600">indah.permata@gmail.com</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-gray-800">password</code>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 text-center mt-4">
                    Semua akun menggunakan password yang sama: <span class="font-mono bg-gray-100 px-2 py-1 rounded">password</span>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
