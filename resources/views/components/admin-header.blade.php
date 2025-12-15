<!-- Desktop Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Title for Desktop -->
            <div class="hidden lg:flex items-center space-x-4">
                <img src="{{ asset('logo/telkom_logo.png') }}" alt="Dokema Logo" class="h-8 w-8">
                <h1 class="text-xl font-semibold text-gray-900">Dokema</h1>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Right side - User info/actions -->
            <div class="flex items-center space-x-4">
                <span class="hidden sm:block text-sm text-gray-700">
                    Sistem Manajemen Magang
                </span>
                <!-- Future: Add user dropdown here -->
            </div>
        </div>
    </div>
</header>
