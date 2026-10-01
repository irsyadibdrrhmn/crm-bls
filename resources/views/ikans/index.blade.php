<x-app-layout>
    <x-slot name="headerTitle">Master Data Komoditas Ikan</x-slot>

    <!-- Header Actions and Breadcrumb -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Katalog Komoditas Ikan Laut & Tawar</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola master data jenis komoditas ikan, spesifikasi satuan grosir, dan harga patokan pasar CV Bakri Laut Sejahtera.</p>
        </div>

        <a href="{{ route('ikans.create') }}" class="btn-primary text-xs py-2.5 px-4 shrink-0 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Komoditas Baru</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="card p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('ikans.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
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
                <div class="w-full sm:w-48">
                    <select name="kategori" class="input-field text-xs">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-secondary text-xs px-4 py-2.5 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                </button>

                @if ($search || $kategori)
                    <a href="{{ route('ikans.index') }}" class="btn-outline text-xs px-3 py-2.5 shrink-0 text-slate-500 hover:text-slate-800" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-16">ID</th>
                        <th>Nama Komoditas Ikan</th>
                        <th>Kategori / Jenis</th>
                        <th>Satuan</th>
                        <th>Harga Jual (IDR)</th>
                        <th>Stok Tersedia</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ikans as $ikan)
                        @php
                            $stokQty = $ikan->stok ? $ikan->stok->jumlah_stok : 0;
                        @endphp
                        <tr>
                            <td class="font-mono text-xs font-semibold text-slate-400">
                                #IKN-{{ str_pad($ikan->id_ikan, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-sm">{{ $ikan->nama_ikan }}</div>
                                <div class="text-[11px] text-slate-400">Diperbarui: {{ $ikan->updated_at ? $ikan->updated_at->format('d/m/Y') : '-' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-slate">
                                    {{ $ikan->jenis_ikan }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600 font-medium">
                                {{ $ikan->satuan }}
                            </td>
                            <td>
                                <span class="font-bold text-slate-900 text-sm">
                                    Rp {{ number_format($ikan->harga_jual, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">/ {{ $ikan->satuan }}</span>
                            </td>
                            <td>
                                @if ($stokQty <= 100)
                                    <span class="badge badge-rose">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        {{ number_format($stokQty, 1) }} {{ $ikan->satuan }} (Kritis)
                                    </span>
                                @elseif ($stokQty <= 250)
                                    <span class="badge badge-amber">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ number_format($stokQty, 1) }} {{ $ikan->satuan }} (Menipis)
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ number_format($stokQty, 1) }} {{ $ikan->satuan }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('ikans.edit', $ikan) }}"
                                       class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-teal-700 hover:border-teal-300 hover:bg-teal-50/50 transition"
                                       title="Ubah Data Ikan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form method="POST"
                                          action="{{ route('ikans.destroy', $ikan) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus komoditas {{ addslashes($ikan->nama_ikan) }}? Tindakan ini akan menghapus stok terkait.');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 transition"
                                                title="Hapus Komoditas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada komoditas ikan ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Sesuaikan kriteria pencarian atau tambahkan jenis komoditas baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ikans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $ikans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
