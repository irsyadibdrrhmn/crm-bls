@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
@endphp

<!-- Desktop Fixed Sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 border-r border-slate-800 bg-slate-900 text-slate-300 lg:flex lg:flex-col shadow-2xl no-print">
    <!-- Brand Header -->
    <div class="p-5 border-b border-slate-800/80 bg-slate-950/40">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-teal-500/20 group-hover:scale-105 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
            </div>
            <div>
                <span class="block text-sm font-bold text-white tracking-tight leading-tight group-hover:text-teal-300 transition">
                    CV BAKRI LAUT
                </span>
                <span class="block text-[10px] text-teal-400 font-semibold tracking-wider uppercase">
                    SEJAHTERA &bull; B2B CRM
                </span>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-6">
        <div>
            <div class="px-2.5 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Menu Operasional
            </div>
            <nav class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard Utama</span>
                </a>

                <!-- Master Ikan (Admin Only) -->
                @if ($isAdmin)
                <a href="{{ route('ikans.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('ikans.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('ikans.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Data Komoditas Ikan</span>
                </a>
                @endif

                <!-- Stok Cold Storage -->
                <a href="{{ route('stoks.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('stoks.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('stoks.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Stok Cold Storage</span>
                </a>

                <!-- Pemesanan B2B -->
                <a href="{{ route('pemesanans.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pemesanans.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('pemesanans.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Pesanan Grosir B2B</span>
                </a>

                <!-- Pengiriman Armada -->
                <a href="{{ route('pengirimans.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('pengirimans.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('pengirimans.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    <span>Logistik & Armada</span>
                </a>
            </nav>
        </div>

        <div>
            <div class="px-2.5 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Keuangan & Laporan
            </div>
            <nav class="space-y-1">
                <!-- Transaksi -->
                <a href="{{ route('transaksis.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('transaksis.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('transaksis.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <span>Faktur & Transaksi</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('laporan.*') ? 'bg-teal-500/15 text-teal-400 border border-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 {{ request()->routeIs('laporan.*') ? 'text-teal-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Laporan & Ekspor</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- User Profile & Session Footer -->
    <div class="p-4 border-t border-slate-800/80 bg-slate-950/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                    {{ strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 1)) }}
                </div>
                <div class="truncate">
                    <span class="block text-xs font-bold text-white truncate max-w-[120px]">
                        {{ $user->name ?? $user->username }}
                    </span>
                    <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold uppercase tracking-wider {{ $isAdmin ? 'bg-cyan-500/20 text-cyan-300' : 'bg-amber-500/20 text-amber-300' }}">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg border border-slate-700 hover:border-rose-500/50 hover:bg-rose-500/10 text-slate-300 hover:text-rose-400 text-xs font-semibold transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar (Logout)</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Sticky Topbar & Menu Drawer -->
<header class="lg:hidden sticky top-0 z-40 bg-slate-900 border-b border-slate-800 px-4 py-3 text-white no-print"
        x-data="{ mobileOpen: false }">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center text-white font-bold text-xs">
                BLS
            </div>
            <div>
                <span class="font-bold text-sm tracking-tight">CV BAKRI LAUT</span>
                <span class="text-[10px] text-teal-400 block -mt-0.5">B2B CRM</span>
            </div>
        </a>

        <button type="button"
                @click="mobileOpen = !mobileOpen"
                class="p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="pt-4 pb-2 border-t border-slate-800 mt-3 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Dashboard</a>
        @if ($isAdmin)
        <a href="{{ route('ikans.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('ikans.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Data Komoditas Ikan</a>
        @endif
        <a href="{{ route('stoks.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('stoks.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Stok Cold Storage</a>
        <a href="{{ route('pemesanans.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('pemesanans.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Pesanan B2B</a>
        <a href="{{ route('pengirimans.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('pengirimans.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Logistik & Armada</a>
        <a href="{{ route('transaksis.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('transaksis.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Faktur Transaksi</a>
        <a href="{{ route('laporan.index') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('laporan.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-300' }}">Laporan & Ekspor</a>

        <div class="pt-3 mt-3 border-t border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-400">{{ $user->name ?? $user->username }} ({{ $user->role }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-semibold">Logout</button>
            </form>
        </div>
    </div>
</header>

