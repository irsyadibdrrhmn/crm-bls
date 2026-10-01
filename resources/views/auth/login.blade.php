<!DOCTYPE html>
<html lang="id" class="h-full bg-navy-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Masuk | CV Bakri Laut Sejahtera</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4 antialiased text-slate-800 bg-slate-900 relative overflow-hidden"
      x-data="{ 
          fillCreds(user, pass) {
              $refs.usernameInput.value = user;
              $refs.passwordInput.value = pass;
          }
      }">

    <!-- Background Maritime Ambient Gradients -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-teal-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-cyan-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Corporate Branding Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-teal-500 to-cyan-400 text-white shadow-lg shadow-teal-500/25 mb-4">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-white font-heading">
                CV BAKRI LAUT SEJAHTERA
            </h1>
            <p class="text-xs text-teal-400 font-medium tracking-wide uppercase mt-1">
                Sistem Informasi Distribusi & Penjualan Ikan B2B
            </p>
        </div>

        <!-- Minimalist Industrial Auth Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xl overflow-hidden p-8 sm:p-9">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Autentikasi Pengguna</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan username dan kredensial akses operasional Anda.</p>
            </div>

            <!-- Flash Error Message -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-lg flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wider">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input id="username"
                               x-ref="usernameInput"
                               type="text"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="contoh: admin / owner"
                               required
                               autofocus
                               class="input-field pl-10 @error('username') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password"
                               x-ref="passwordInput"
                               type="password"
                               name="password"
                               placeholder="••••••••"
                               required
                               class="input-field pl-10 @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-xs text-slate-600 font-medium">Ingat Sesi Ini</span>
                    </label>
                    <span class="text-[11px] text-slate-400">Distribusi Terenkripsi</span>
                </div>

                <button type="submit" class="w-full btn-primary py-2.5 text-sm font-semibold tracking-wide">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Quick Demo Credential Pills -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2.5 text-center">Akses Cepat Pengujian (Role)</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            @click="fillCreds('admin', 'admin123')"
                            class="p-2.5 text-left rounded-lg border border-slate-200 hover:border-teal-500 hover:bg-teal-50/50 transition group">
                        <div class="text-[11px] font-bold text-slate-800 group-hover:text-teal-700">Admin Operasional</div>
                        <div class="text-[10px] text-slate-400 font-mono">admin / admin123</div>
                    </button>
                    <button type="button"
                            @click="fillCreds('owner', 'owner123')"
                            class="p-2.5 text-left rounded-lg border border-slate-200 hover:border-amber-500 hover:bg-amber-50/50 transition group">
                        <div class="text-[11px] font-bold text-slate-800 group-hover:text-amber-700">Owner Eksekutif</div>
                        <div class="text-[10px] text-slate-400 font-mono">owner / owner123</div>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} CV Bakri Laut Sejahtera. Hak Cipta Dilindungi.
        </p>
    </div>
</body>
</html>

