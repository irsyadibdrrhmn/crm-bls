<x-app-layout>
    <x-slot name="headerTitle">Manajemen Stok & Cold Storage</x-slot>

    <!-- Top Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Kapasitas Terisi</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">
                {{ number_format($totalStok, 1, ',', '.') }} <span class="text-xs font-semibold text-slate-400">Kg</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi seluruh ruang pendingin</p>
        </div>

        <div class="card p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Stok Aman (>250 Kg)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-emerald-700 mt-2">
                {{ $countAman }} <span class="text-xs font-semibold text-slate-400">Komoditas</span>
            </div>
            <p class="text-[11px] text-emerald-600/80 mt-1">Ketersediaan siap suplai pesanan B2B</p>
        </div>

        <div class="card p-5 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Stok Menipis (100-250 Kg)</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-amber-700 mt-2">
                {{ $countMenipis }} <span class="text-xs font-semibold text-slate-400">Komoditas</span>
            </div>
            <p class="text-[11px] text-amber-600/80 mt-1">Perlu penambahan pasokan segera</p>
        </div>

        <div class="card p-5 border-l-4 border-rose-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Stok Kritis (<100 Kg)</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-rose-700 mt-2">
                {{ $countKritis }} <span class="text-xs font-semibold text-rose-500">Perlu Restock</span>
            </div>
            <p class="text-[11px] text-rose-600/80 mt-1">Prioritas pengadaan cold room</p>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="card p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('stoks.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari berdasarkan nama ikan atau jenis komoditas..."
                       class="input-field pl-10 text-xs">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="w-full sm:w-56">
                    <select name="status" class="input-field text-xs">
                        <option value="">Semua Kondisi Stok</option>
                        <option value="aman" {{ $status === 'aman' ? 'selected' : '' }}>Stok Aman (> 250 Kg)</option>
                        <option value="menipis" {{ $status === 'menipis' ? 'selected' : '' }}>Stok Menipis (100 - 250 Kg)</option>
                        <option value="kritis" {{ $status === 'kritis' ? 'selected' : '' }}>Stok Kritis (< 100 Kg)</option>
                    </select>
                </div>

                <button type="submit" class="btn-secondary text-xs px-4 py-2.5 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                </button>

                @if ($search || $status)
                    <a href="{{ route('stoks.index') }}" class="btn-outline text-xs px-3 py-2.5 shrink-0 text-slate-500 hover:text-slate-800" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of Stocks Card -->
    <div class="card" x-data="{ adjustModalOpen: false, currentStok: null }">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-20">ID Stok</th>
                        <th>Komoditas Ikan</th>
                        <th>Kategori</th>
                        <th>Kuantitas Tersimpan</th>
                        <th>Ambang Status</th>
                        <th>Terakhir Diperbarui</th>
                        @if (auth()->user()->isAdmin())
                            <th class="text-right">Aksi Penyesuaian</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stoks as $stok)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-slate-400">
                                #STK-{{ str_pad($stok->id_stok, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-sm">{{ $stok->ikan->nama_ikan }}</div>
                                <div class="text-[11px] text-slate-400">ID Ikan: #{{ $stok->id_ikan }}</div>
                            </td>
                            <td>
                                <span class="badge badge-slate">
                                    {{ $stok->ikan->jenis_ikan }}
                                </span>
                            </td>
                            <td>
                                <span class="text-base font-extrabold text-slate-900">
                                    {{ number_format($stok->jumlah_stok, 1, ',', '.') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500">
                                    {{ $stok->ikan->satuan }}
                                </span>
                            </td>
                            <td>
                                @if ($stok->jumlah_stok <= 100)
                                    <span class="badge badge-rose">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        Kritis
                                    </span>
                                @elseif ($stok->jumlah_stok <= 250)
                                    <span class="badge badge-amber">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menipis
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aman
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">
                                {{ $stok->tanggal_update ? $stok->tanggal_update->format('d/m/Y H:i') : '-' }}
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td class="text-right">
                                    <button type="button"
                                            @click="currentStok = {{ json_encode([
                                                'id' => $stok->id_stok,
                                                'nama' => $stok->ikan->nama_ikan,
                                                'jumlah' => (float)$stok->jumlah_stok,
                                                'satuan' => $stok->ikan->satuan,
                                                'update_url' => route('stoks.update', $stok)
                                            ]) }}; adjustModalOpen = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200 bg-teal-50 text-teal-700 text-xs font-semibold hover:bg-teal-600 hover:text-white hover:border-teal-600 transition shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span>Audit / Restock</span>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada data stok ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter kondisi stok Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($stoks->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $stoks->links() }}
            </div>
        @endif

        <!-- Adjustment / Restock Modal for Admin -->
        <div x-show="adjustModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             x-transition.opacity>
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200"
                 @click.away="adjustModalOpen = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Perbarui Stok Cold Storage</span>
                    </h3>
                    <button @click="adjustModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
                </div>

                <template x-if="currentStok">
                    <form :action="currentStok.update_url" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                            <span class="text-slate-500 block">Komoditas Terpilih:</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block" x-text="currentStok.nama"></span>
                            <div class="mt-1 text-slate-600 font-medium">
                                Stok fisik tercatat: <strong class="text-teal-700" x-text="currentStok.jumlah"></strong> <span x-text="currentStok.satuan"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenis Operasi Inventori
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-teal-50/50 hover:border-teal-300 transition">
                                    <input type="radio" name="tipe" value="tambah" checked class="text-teal-600 focus:ring-teal-500">
                                    <span class="text-xs font-semibold text-slate-800">Restock Masuk (+)</span>
                                </label>
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-teal-50/50 hover:border-teal-300 transition">
                                    <input type="radio" name="tipe" value="koreksi" class="text-teal-600 focus:ring-teal-500">
                                    <span class="text-xs font-semibold text-slate-800">Koreksi Opname (=)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kuantitas (Sesuai Satuan) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   name="jumlah"
                                   step="0.5"
                                   min="0.1"
                                   required
                                   placeholder="Masukkan kuantitas..."
                                   class="input-field">
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" @click="adjustModalOpen = false" class="btn-outline text-xs py-2 px-3.5">
                                Batal
                            </button>
                            <button type="submit" class="btn-primary text-xs py-2 px-4 shadow-sm">
                                Simpan Perubahan Stok
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
