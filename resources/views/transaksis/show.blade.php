<x-app-layout>
    <x-slot name="headerTitle">Faktur Transaksi #TRX-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Actions Toolbar -->
        <div class="flex items-center justify-between no-print">
            <a href="{{ route('transaksis.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 flex items-center gap-1">
                &larr; Kembali ke Catatan Transaksi
            </a>

            <button onclick="window.print()" class="btn-primary text-xs py-2 px-4 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Faktur Resmi</span>
            </button>
        </div>

        <!-- Official Invoice Paper Container -->
        <div class="card p-8 sm:p-10 bg-white border border-slate-200 shadow-lg text-slate-800">
            <!-- Header Invoice -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6 border-b-2 border-slate-900">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-navy-950 text-white flex items-center justify-center font-bold text-lg">
                            BLS
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900 leading-tight">CV BAKRI LAUT SEJAHTERA</h2>
                            <p class="text-[11px] font-semibold tracking-wider text-teal-700 uppercase">Distribusi Hasil Laut & Cold Storage B2B</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        Kawasan Pelabuhan Perikanan Samudera Jakarta<br>
                        Telp / CS: (021) 668-9821 &bull; info@bakrilautsejahtera.co.id
                    </p>
                </div>

                <div class="text-left sm:text-right">
                    <span class="inline-block px-3 py-1 bg-teal-100 text-teal-900 text-xs font-black uppercase rounded mb-2">
                        FAKTUR PENJUALAN B2B
                    </span>
                    <div class="font-mono text-base font-bold text-slate-900">
                        #TRX-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        Tanggal: {{ $transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->format('d F Y, H:i') : '-' }} WIB
                    </div>
                </div>
            </div>

            <!-- Billing & Shipping Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Ditagihkan Kepada (Pembeli / Mitra):</span>
                    <div class="text-sm font-bold text-slate-900">{{ $transaksi->pemesanan?->nama_pelanggan }}</div>
                    <div class="mt-1 flex items-center gap-1.5 font-mono text-xs text-slate-800">
                        <span class="text-slate-500 font-sans font-semibold">NIK Pembeli:</span>
                        <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200 font-bold text-slate-900">
                            {{ $transaksi->pemesanan?->nik ? trim(chunk_split($transaksi->pemesanan->nik, 4, ' ')) : '-' }}
                        </span>
                    </div>
                    <div class="text-slate-600 mt-1">{{ $transaksi->pemesanan?->alamat_tujuan }}</div>
                    <div class="text-slate-500 mt-0.5">Kontak PIC: {{ $transaksi->pemesanan?->no_telp ?? '-' }}</div>
                </div>

                <div class="sm:text-right">
                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Referensi Pesanan & Armada:</span>
                    <div class="font-bold text-slate-900">No. PO: PO-{{ str_pad($transaksi->id_pemesanan, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-slate-600 mt-0.5">Armada: {{ $transaksi->pemesanan?->pengiriman?->nama_kurir_armada ?? 'Armada Pendingin BLS' }}</div>
                    <div class="text-slate-500 mt-0.5">Status Pengiriman: <strong class="text-emerald-700 capitalize">{{ str_replace('_', ' ', $transaksi->pemesanan?->pengiriman?->status_kirim ?? 'Selesai') }}</strong></div>

                    <!-- Metode Pembayaran Badge -->
                    <div class="mt-2.5">
                        <span class="font-bold text-slate-400 uppercase tracking-wider block mb-0.5 text-[10px]">Kanal Pembayaran:</span>
                        @php
                            $metode = $transaksi->metode_pembayaran ?? $transaksi->pemesanan?->metode_pembayaran ?? 'transfer_bank';
                            $bank = $transaksi->nama_bank ?? $transaksi->pemesanan?->nama_bank ?? 'BCA';
                        @endphp
                        @if ($metode === 'cash')
                            <span class="inline-block px-2.5 py-1 bg-amber-100 border border-amber-300 text-amber-900 font-black uppercase rounded text-[11px]">
                                TUNAI / CASH (PELABUHAN)
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 bg-sky-100 border border-sky-300 text-sky-900 font-black uppercase rounded text-[11px]">
                                TRANSFER BANK ({{ $bank }})
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="py-6">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-300 text-slate-500 uppercase tracking-wider text-[11px]">
                            <th class="py-2">No</th>
                            <th class="py-2">Deskripsi Komoditas Ikan</th>
                            <th class="py-2 text-right">Kuantitas</th>
                            <th class="py-2 text-right">Harga Satuan</th>
                            <th class="py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if ($transaksi->pemesanan && $transaksi->pemesanan->items)
                            @foreach ($transaksi->pemesanan->items as $i => $item)
                                <tr>
                                    <td class="py-3 text-slate-400">{{ $i + 1 }}</td>
                                    <td class="py-3">
                                        <div class="font-bold text-slate-900">{{ $item->ikan->nama_ikan }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $item->ikan->jenis_ikan }}</div>
                                    </td>
                                    <td class="py-3 text-right font-semibold text-slate-800">
                                        {{ number_format($item->jumlah, 1) }} {{ $item->ikan->satuan }}
                                    </td>
                                    <td class="py-3 text-right text-slate-600">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-right font-bold text-slate-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-3 text-slate-700">
                                    {{ $transaksi->list_item }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-900 font-bold">
                            <td colspan="4" class="pt-4 text-right text-xs uppercase tracking-wider text-slate-600">Total Pembayaran Resmi (IDR):</td>
                            <td class="pt-4 text-right text-base text-slate-900">
                                Rp {{ number_format($transaksi->pemesanan?->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Instruction / Bank Details Section -->
            @php
                $metode = $transaksi->metode_pembayaran ?? $transaksi->pemesanan?->metode_pembayaran ?? 'transfer_bank';
                $bank = $transaksi->nama_bank ?? $transaksi->pemesanan?->nama_bank ?? 'BCA';
            @endphp
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs mb-6">
                @if ($metode === 'cash')
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-amber-100 text-amber-800 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 uppercase tracking-wider block">Pembayaran Tunai di Lokasi (Cash on Delivery / Timbang Dermaga)</span>
                            <p class="text-slate-600 mt-0.5">
                                Pembayaran diserahkan secara kontan saat serah terima fisik komoditas di cold storage pelabuhan setelah penimbangan tonase resmi.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-sky-100 text-sky-800 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 uppercase tracking-wider block">Instruksi Pembayaran Transfer Rekening Bank Resmi CV BLS</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-1.5 text-slate-700">
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Bank Tujuan:</span>
                                    <span class="font-bold text-slate-900">{{ $bank == 'Mandiri' ? 'Bank Mandiri' : ($bank == 'BRI' ? 'Bank BRI' : ($bank == 'BNI' ? 'Bank BNI' : 'Bank Central Asia (BCA)')) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Nomor Rekening & Atas Nama:</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $bank == 'Mandiri' ? '120-00-9831-291' : ($bank == 'BRI' ? '0341-01-002931-502' : ($bank == 'BNI' ? '0918-2831-22' : '8820-9182-33')) }}</span> &bull; <strong>CV Bakri Laut Sejahtera</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Signatures / Stamp Block -->
            <div class="grid grid-cols-2 gap-8 pt-8 mt-4 border-t border-slate-200 text-xs">
                <div class="text-center">
                    <p class="text-slate-400 mb-14">Diterima oleh Pembeli / Mitra:</p>
                    <div class="border-t border-slate-400 w-48 mx-auto pt-1 font-bold text-slate-800">
                        {{ $transaksi->pemesanan?->nama_pelanggan }}
                        <div class="text-[10px] text-slate-500 font-mono font-normal">NIK: {{ $transaksi->pemesanan?->nik ?? '-' }}</div>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-slate-400 mb-14">Petugas Admin Operasional:</p>
                    <div class="border-t border-slate-400 w-48 mx-auto pt-1 font-bold text-slate-800">
                        {{ $transaksi->user?->name ?? $transaksi->user?->username ?? 'Admin CV BLS' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

