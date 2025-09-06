@php
    $activeClasses = 'bg-gray-800 text-white';
    $inactiveClasses = 'text-gray-600 hover:bg-gray-200';
@endphp
<sidebar class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto transition duration-300 transform bg-gray-50 border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-0" :class="{ 'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen }">
    <div class="flex items-center justify-start h-16 px-6 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="text-xl font-medium text-gray-800">
            Magang Panel
        </a>
    </div>
    <nav class="mt-4 space-y-2 px-3">
        <div>
            <a href="{{ route('dashboard') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('dashboard*') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0a2 2 0 01-2 2H10a2 2 0 01-2-2v0z"></path>
                </svg>
                <span class="mx-3">Dashboard</span>
            </a>
        </div>
        <div>
            <a href="{{ route('workflow.approval') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('workflow.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="mx-3">Approval Workflow</span>
            </a>
        </div>
        <div>
            <a href="{{ route('user.index') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('user.*') ? $activeClasses : $inactiveClasses }}">
                <x-lucide-users class="h-5 w-5" />
                <span class="mx-3">User</span>
            </a>
        </div>
        <div>
            <a href="{{ route('profil.index') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('profil.*') ? $activeClasses : $inactiveClasses }}">
                <x-lucide-user class="h-5 w-5" />
                <span class="mx-3">Profil</span>
            </a>
        </div>
        <div>
            <a href="{{ route('magang.index') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('magang.*') ? $activeClasses : $inactiveClasses }}">
                <x-lucide-briefcase class="h-5 w-5" />
                <span class="mx-3">Magang</span>
            </a>
        </div>
        <div>
            <a href="{{ route('laporan.index', 1) }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('laporan.*') ? $activeClasses : $inactiveClasses }}">
                <x-lucide-file-text class="h-5 w-5" />
                <span class="mx-3">Laporan</span>
            </a>
        </div>
        <div>
            <a href="{{ route('bimbingan.index', 1) }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('bimbingan.*') ? $activeClasses : $inactiveClasses }}">
                <x-lucide-book-open class="h-5 w-5" />
                <span class="mx-3">Bimbingan</span>
            </a>
        </div>
        <div>
            <a href="{{ route('penilaian.index') }}" class="px-3 py-2 flex items-center rounded-sm {{ request()->routeIs('penilaian.*') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <span class="mx-3">Penilaian</span>
            </a>
        </div>
        <div>
            <a href="/logout" class="px-3 py-2 flex items-center rounded-sm text-red-600 hover:bg-red-200 hover:text-red-800">
                <x-lucide-log-out class="h-5 w-5" />
                <span class="mx-3">Logout</span>
            </a>
        </div>
    </nav>
</sidebar>
