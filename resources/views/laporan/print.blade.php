<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Penjualan B2B - CV Bakri Laut Sejahtera</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: white !important; font-size: 11pt; }
            .no-print { display: none !important; }
            .print-border { border: 1px solid #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 text-slate-800">

    <div class="max-w-4xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('laporan.index') }}" class="btn-secondary text-xs">
            &larr; Kembali ke Sistem
        </a>
        <button onclick="window.print()" class="btn-primary text-xs px-4">
            Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 border border-slate-200 shadow-lg rounded-xl print:shadow-none print:border-none print:p-0">
        <!-- Letterhead Header -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold text-xl">
                    BLS
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900">CV BAKRI LAUT SEJAHTERA</h1>
                    <p class="text-xs font-bold text-teal-700 uppercase tracking-wider">Distribusi & Pergudangan Hasil Laut Cold Chain</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Kawasan Pelabuhan Perikanan Samudera &bull; Jakarta Utara &bull; Telp: (021) 668-9821
                    </p>
                </div>
            </div>

            <div class="text-right">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Laporan Resmi</div>
                <div class="text-base font-black text-slate-900">PENJUALAN B2B</div>
                <div class="text-xs text-slate-500 mt-0.5">
                    Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
                </div>
            </div>
        </div>

        <!-- Metric Recap -->
        <div class="grid grid-cols-3 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200 mb-6 text-center text-xs">
            <div>
                <span class="text-slate-500 block uppercase font-semibold text-[10px]">Total Pendapatan</span>
                <span class="text-base font-black text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-semibold text-[10px]">Total Tonase Terjual</span>
                <span class="text-base font-black text-slate-900">{{ number_format($totalVolumeKg, 1, ',', '.') }} Kg</span>
            </div>
            <div>
                <span class="text-slate-500 block uppercase font-semibold text-[10px]">Total Transaksi</span>
                <span class="text-base font-black text-slate-900">{{ $transaksis->count() }} Transaksi</span>
            </div>
        </div>

        <!-- Table of transactions -->
        <div class="mb-8">
            <table class="w-full text-left text-xs border border-slate-200">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-300 uppercase text-[10px]">
                        <th class="p-2">No. TRX</th>
                        <th class="p-2">No. PO</th>
                        <th class="p-2">Tanggal</th>
                        <th class="p-2">Pelanggan & NIK</th>
                        <th class="p-2">Metode Bayar</th>
                        <th class="p-2">Komoditas & Kuantitas</th>
                        <th class="p-2 text-right">Nominal (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($transaksis as $t)
                        <tr>
                            <td class="p-2 font-mono">TRX-{{ str_pad($t->id_transaksi, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-2 font-mono">PO-{{ str_pad($t->id_pemesanan, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-2">{{ $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d/m/Y') : '-' }}</td>
                            <td class="p-2">
                                <div class="font-semibold text-slate-900">{{ $t->pemesanan?->nama_pelanggan }}</div>
                                <div class="font-mono text-[9px] text-slate-500">NIK: {{ $t->pemesanan?->nik ?? '-' }}</div>
                            </td>
                            <td class="p-2 text-[10px]">
                                @php
                                    $metode = $t->metode_pembayaran ?? $t->pemesanan?->metode_pembayaran ?? 'transfer_bank';
                                    $bank = $t->nama_bank ?? $t->pemesanan?->nama_bank;
                                @endphp
                                @if ($metode === 'cash')
                                    <span class="font-bold text-amber-900">Tunai (Cash)</span>
                                @else
                                    <span class="font-bold text-sky-900">Transfer {{ $bank ? "($bank)" : '' }}</span>
                                @endif
                            </td>
                            <td class="p-2 text-slate-600 text-[10px]">{{ $t->list_item }}</td>
                            <td class="p-2 text-right font-bold text-slate-900">
                                Rp {{ number_format($t->pemesanan?->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-slate-400">Tidak ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-800 font-bold bg-slate-50">
                        <td colspan="6" class="p-3 text-right uppercase text-slate-700">Total Akumulasi:</td>
                        <td class="p-3 text-right text-sm text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Sign-off block -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-200 text-xs">
            <div class="text-center">
                <p class="text-slate-500 mb-16">Disiapkan oleh Manager Operasional,</p>
                <div class="border-t border-slate-400 w-44 mx-auto pt-1 font-bold text-slate-900">
                    Operational Manager
                </div>
            </div>

            <div class="text-center">
                <p class="text-slate-500 mb-16">Mengetahui & Menyetujui,</p>
                <div class="border-t border-slate-400 w-44 mx-auto pt-1 font-bold text-slate-900">
                    H. Bakri (Owner CV BLS)
                </div>
            </div>
        </div>
    </div>

</body>
</html>

