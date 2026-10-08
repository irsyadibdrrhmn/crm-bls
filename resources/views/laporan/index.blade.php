<x-app-layout>
    <x-slot name="headerTitle">Laporan Penjualan & Distribusi Hasil Laut</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Rekapitulasi Penjualan & Kinerja Distribusi B2B</h2>
            <p class="text-xs text-slate-500">Analisis komprehensif transaksi grosir ikan, volume tonase keluar, dan pendapatan operasional.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('laporan.export.csv', ['dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}"
               class="btn-secondary text-xs py-2 px-3.5 shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel / CSV</span>
            </a>

            <a href="{{ route('laporan.print', ['dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}"
               target="_blank"
               class="btn-primary text-xs py-2 px-3.5 shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Cetak PDF</span>
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Periode Dari Tanggal</label>
                <input type="date"
                       name="dari_tanggal"
                       value="{{ $dariTanggal }}"
                       class="input-field text-xs">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date"
                       name="sampai_tanggal"
                       value="{{ $sampaiTanggal }}"
                       class="input-field text-xs">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-secondary text-xs px-4 py-2.5 flex-1">
                    Terapkan Periode
                </button>
                <a href="{{ route('laporan.index') }}" class="btn-outline text-xs px-3 py-2.5 text-slate-500">
                    Bulan Ini
                </a>
            </div>
        </form>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card p-5">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Omzet Penjualan</span>
            <div class="text-2xl font-black text-slate-900 mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <p class="text-xs text-teal-700 font-medium mt-1">Periode {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}</p>
        </div>

        <div class="card p-5">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Volume Ikan Terjual</span>
            <div class="text-2xl font-black text-slate-900 mt-2">
                {{ number_format($totalVolumeKg, 1, ',', '.') }} <span class="text-sm font-semibold text-slate-500">Kg</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Estimasi {{ number_format($totalVolumeKg / 1000, 2) }} Ton</p>
        </div>

        <div class="card p-5">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Jumlah Transaksi Dibukukan</span>
            <div class="text-2xl font-black text-slate-900 mt-2">
                {{ $totalTransaksi }} <span class="text-sm font-semibold text-slate-500">Transaksi</span>
            </div>
            <p class="text-xs text-emerald-700 font-medium mt-1">Semua pesanan telah diverifikasi admin</p>
        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="card overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Rincian Transaksi Penjualan</h3>
            <span class="text-xs text-slate-500 font-medium">{{ $transaksis->count() }} Data Ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-24">No. Transaksi</th>
                        <th>No. PO</th>
                        <th>Tanggal</th>
                        <th>Pelanggan & NIK</th>
                        <th>Metode Bayar</th>
                        <th>Daftar Komoditas (List Item)</th>
                        <th>Status Order</th>
                        <th>Petugas Admin</th>
                        <th class="text-right">Nominal (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transaksis as $t)
                        <tr>
                            <td class="font-mono font-bold text-xs text-slate-900">
                                TRX-{{ str_pad($t->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <a href="{{ route('pemesanans.show', $t->id_pemesanan) }}" class="text-xs font-semibold text-teal-700 hover:underline">
                                    PO-{{ str_pad($t->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td class="text-xs text-slate-600 whitespace-nowrap">
                                {{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $t->pemesanan?->nama_pelanggan }}</div>
                                <div class="font-mono text-[10px] text-teal-800 mt-0.5">NIK: {{ $t->pemesanan?->nik ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $metode = $t->metode_pembayaran ?? $t->pemesanan?->metode_pembayaran ?? 'transfer_bank';
                                    $bank = $t->nama_bank ?? $t->pemesanan?->nama_bank;
                                @endphp
                                @if ($metode === 'cash')
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 whitespace-nowrap">
                                        Tunai (Cash)
                                    </span>
                                @else
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200 whitespace-nowrap">
                                        Transfer {{ $bank ? "($bank)" : '' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-600 max-w-xs truncate" title="{{ $t->list_item }}">
                                {{ $t->list_item }}
                            </td>
                            <td>
                                <span class="badge bg-slate-100 text-slate-700 capitalize">
                                    {{ $t->pemesanan?->status_pesan ?? '-' }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-600">
                                {{ $t->user?->name ?? $t->user?->username ?? 'Admin' }}
                            </td>
                            <td class="text-right font-bold text-slate-900">
                                Rp {{ number_format($t->pemesanan?->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-400 text-xs">
                                Tidak ada transaksi penjualan pada rentang tanggal yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-300">
                            <td colspan="8" class="px-4 py-3 text-right text-xs uppercase tracking-wider text-slate-700">
                                Total Akumulasi Periode:
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-slate-900">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-app-layout>

