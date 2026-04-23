<!DOCTYPE html>
<html lang="id" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wellness') — Mental Health Monitoring</title>

    {{-- Tailwind CSS CDN (ganti dengan Vite/mix di production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#0f766e', 50: '#f0fdfa', 500: '#14b8a6', 700: '#0f766e', 900: '#134e4a' },
                        risk: {
                            normal: '#22c55e',
                            ringan: '#eab308',
                            sedang: '#f97316',
                            tinggi: '#ef4444',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-300 min-h-screen">

    {{-- ── SIDEBAR ───────────────────────────────────────────────────────── --}}
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="flex h-screen overflow-hidden">

        {{-- Overlay (mobile) --}}
        <div x-show="sidebarOpen && window.innerWidth < 1024"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside x-show="sidebarOpen"
               class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col shadow-xl lg:static lg:shadow-none transition-transform duration-300">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="w-9 h-9 rounded-xl bg-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-sm text-teal-700 dark:text-teal-400 leading-tight">WellnessApp</div>
                    <div class="text-xs text-slate-400">Mental Health Monitor</div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="px-4 py-3 mx-3 mt-3 rounded-xl bg-slate-50 dark:bg-slate-700/50">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ auth()->user()->role->display_name }}</p>
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->unit }}</p>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php $role = auth()->user()->role->name; @endphp

                @if(in_array($role, ['admin', 'wellness_warrior', 'psikolog']))
                    @include('layouts.partials.nav-admin')
                @else
                    @include('layouts.partials.nav-pegawai')
                @endif
            </nav>

            {{-- Bottom: Theme Toggle --}}
            <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <span class="text-xs text-slate-500 dark:text-slate-400">Tema</span>
                <button x-data @click="
                    const html = document.documentElement;
                    const isDark = html.classList.toggle('dark');
                    fetch('{{ route('theme.toggle') }}', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                        body: JSON.stringify({theme: isDark ? 'dark' : 'light'})
                    });
                " class="relative w-12 h-6 rounded-full bg-slate-200 dark:bg-teal-600 transition-colors duration-300 focus:outline-none">
                    <span class="absolute top-0.5 left-0.5 dark:left-6 w-5 h-5 rounded-full bg-white shadow transition-all duration-300 flex items-center justify-center text-xs">
                        <span class="dark:hidden">☀️</span>
                        <span class="hidden dark:block">🌙</span>
                    </span>
                </button>
            </div>

            {{-- Logout --}}
            <div class="px-3 pb-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN CONTENT ─────────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center px-4 gap-4 shadow-sm">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-base font-semibold flex-1">@yield('page-title', 'Dashboard')</h1>

                {{-- Notifications bell --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unread_notifications_count > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] flex items-center justify-center font-bold">
                                {{ auth()->user()->unread_notifications_count }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                            <span class="font-semibold text-sm">Notifikasi</span>
                            <a href="{{ route('notifications.read-all') }}" class="text-xs text-teal-600 hover:underline" onclick="event.preventDefault(); document.getElementById('notif-read-all').submit();">
                                Tandai semua dibaca
                            </a>
                            <form id="notif-read-all" action="{{ route('notifications.read-all') }}" method="POST" class="hidden">@csrf</form>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $notif)
                                <div class="px-4 py-3 {{ $notif->is_read ? 'opacity-60' : 'bg-teal-50/50 dark:bg-teal-900/10' }}">
                                    <p class="text-sm font-medium">{{ $notif->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $notif->message }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-sm text-slate-400">Tidak ada notifikasi</div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-700">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-teal-600 hover:underline">Lihat semua →</a>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mx-4 mt-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 rounded-xl text-sm flex items-center justify-between">
                    <span>
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 mt-4 px-4 py-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 rounded-xl text-sm">
                    <svg class="w-4 h-4 mr-1.5 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
