<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Distribusi & Penjualan Ikan B2B' }} | CV Bakri Laut Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="h-full antialiased font-sans text-slate-800 bg-slate-100 selection:bg-teal-500 selection:text-white">
    <div class="min-h-full flex flex-col">
        <!-- Sidebar Navigation -->
        @include('layouts.navigation')

        <!-- Main Workspace Area -->
        <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
            <!-- Persistent Top Header -->
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200 shadow-xs no-print">
                <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                    <!-- Title & Breadcrumb -->
                    <div class="flex items-center gap-3">
                        <div>
                            <span class="text-[11px] font-semibold tracking-wider text-teal-700 uppercase">CV Bakri Laut Sejahtera</span>
                            <h1 class="text-base font-bold text-slate-900 leading-tight">
                                {{ $headerTitle ?? 'Portal Distribusi & Operasional B2B' }}
                            </h1>
                        </div>
                    </div>

                    <!-- Top Right Quick Info -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-slate-600 font-medium">Sistem:</span>
                            <span class="font-semibold text-slate-900">Cold Chain Aktif</span>
                        </div>

                        <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>

                        <!-- User Profile Indicator -->
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-navy-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 2)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <span class="block text-xs font-bold text-slate-800 leading-none">{{ auth()->user()->name ?? auth()->user()->username }}</span>
                                <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[10px] font-bold uppercase {{ auth()->user()->role === 'admin' ? 'bg-cyan-100 text-cyan-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Notification Alerts -->
            <div class="px-4 sm:px-6 lg:px-8 pt-4 no-print">
                @if (session('success'))
                    <div class="flex items-center gap-3 p-3.5 mb-4 text-xs font-medium text-emerald-800 bg-emerald-50 rounded-lg border border-emerald-200 shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="flex-1">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center gap-3 p-3.5 mb-4 text-xs font-medium text-rose-800 bg-rose-50 rounded-lg border border-rose-200 shadow-xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="flex-1">{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 mb-4 text-xs font-medium text-rose-800 bg-rose-50 rounded-lg border border-rose-200 shadow-xs">
                        <p class="font-bold mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Dynamic Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 pb-10">
                {{ $slot }}
            </main>

            <!-- Bottom Industrial Status Footer -->
            <footer class="mt-auto border-t border-slate-200 bg-white py-3 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 no-print">
                <div>
                    <span class="font-bold text-slate-700">CV Bakri Laut Sejahtera</span> &mdash; B2B Marine Seafood Distribution & Logistics Management System
                </div>
                <div class="flex items-center gap-4 text-slate-400">
                    <span>Versi Produksi 2.4</span>
                    <span>&bull;</span>
                    <span>Pelabuhan Perikanan Samudera</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

