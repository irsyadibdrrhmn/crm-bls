<x-app-layout>
    <x-slot name="headerTitle">Catatan Transaksi & Pembukuan Penjualan</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Jurnal Transaksi Resmi Penjualan Grosir B2B</h2>
            <p class="text-xs text-slate-500 mt-1">Rekapitulasi transaksi penjualan hasil laut yang telah diverifikasi dan diproses oleh admin operasional.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('laporan.index') }}" class="btn-primary text-xs py-2.5 px-4 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Buka Modul Laporan</span>
            </a>
        </div>
    </div>

    <!-- Total Revenue Summary Banner -->
    <div class="card p-6 bg-slate-900 text-white mb-6 shadow-md border-0 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-teal-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-teal-400 uppercase tracking-widest block">Total Akumulasi Transaksi B2B</span>
                    <p class="text-xs text-slate-300 mt-0.5">Nilai transaksi penjualan yang telah sah tercatat dalam pembukuan resmi</p>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Rp {{ number_format($totalNominal, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="card p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('transaksis.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cari Transaksi / Pelanggan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="No. TRX atau nama pembeli..."
                           class="input-field pl-10 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date"
                       name="dari_tanggal"
                       value="{{ $dariTanggal }}"
                       class="input-field text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date"
                       name="sampai_tanggal"
                       value="{{ $sampaiTanggal }}"
                       class="input-field text-xs">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-secondary text-xs py-2.5 px-4 flex-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter Data</span>
                </button>
                @if ($search || $dariTanggal || $sampaiTanggal)
                    <a href="{{ route('transaksis.index') }}" class="btn-outline text-xs px-3 py-2.5 text-slate-500 hover:text-slate-800" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Transaction Table Card -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-28">No. Transaksi</th>
                        <th>No. PO</th>
                        <th>Waktu Transaksi</th>
                        <th>Pelanggan B2B</th>
                        <th>Rincian Komoditas (List Item)</th>
                        <th>Total Nilai (IDR)</th>
                        <th>Petugas Admin</th>
                        <th class="text-right">Faktur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $trx)
                        <tr>
                            <td class="font-mono font-bold text-xs text-slate-900">
                                TRX-{{ str_pad($trx->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <a href="{{ route('pemesanans.show', $trx->id_pemesanan) }}" class="font-mono font-bold text-xs text-teal-700 hover:underline">
                                    PO-{{ str_pad($trx->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td class="text-xs text-slate-600 whitespace-nowrap">
                                {{ $trx->tanggal_transaksi ? $trx->tanggal_transaksi->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-xs">
                                    {{ $trx->pemesanan?->nama_pelanggan }}
                                </div>
                            </td>
                            <td class="text-xs text-slate-600 max-w-sm truncate" title="{{ $trx->list_item }}">
                                {{ $trx->list_item }}
                            </td>
                            <td>
                                <span class="font-extrabold text-slate-900 text-sm">
                                    Rp {{ number_format($trx->pemesanan?->total_harga ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600">
                                {{ $trx->admin?->name ?? 'Admin Operasional' }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('transaksis.show', $trx) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200 bg-teal-50 text-teal-700 text-xs font-semibold hover:bg-teal-600 hover:text-white hover:border-teal-600 transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    <span>Cetak Faktur</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada jurnal transaksi ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter tanggal atau pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
