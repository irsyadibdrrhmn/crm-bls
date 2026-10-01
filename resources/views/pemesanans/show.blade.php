<x-app-layout>
    <x-slot name="headerTitle">Manifes & Rincian Pesanan #PO-{{ str_pad($pemesanan->id_pemesanan, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Top Navigation & Status Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('pemesanans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Daftar Pesanan
                </a>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900">
                        PO-{{ str_pad($pemesanan->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                    @if ($pemesanan->status_pesan === 'pending')
                        <span class="badge bg-amber-100 text-amber-800 text-xs px-3 py-1 font-bold">
                            Menunggu Konfirmasi Admin
                        </span>
                    @elseif ($pemesanan->status_pesan === 'diproses')
                        <span class="badge bg-sky-100 text-sky-800 text-xs px-3 py-1 font-bold">
                            Terkonfirmasi & Diproses
                        </span>
                    @elseif ($pemesanan->status_pesan === 'dikirim')
                        <span class="badge bg-indigo-100 text-indigo-800 text-xs px-3 py-1 font-bold">
                            Dalam Pengiriman Armada
                        </span>
                    @else
                        <span class="badge bg-emerald-100 text-emerald-800 text-xs px-3 py-1 font-bold">
                            Pesanan Selesai / Terkirim
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if (auth()->user()->isAdmin() && $pemesanan->status_pesan === 'pending')
                    <form method="POST" action="{{ route('pemesanans.destroy', $pemesanan) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan pending ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-xs py-2 px-3">
                            Batalkan PO
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Admin Operational Action Callout: Confirmation & Auto Stock Deduction -->
        @if (auth()->user()->isAdmin() && $pemesanan->status_pesan === 'pending')
            <div class="p-5 rounded-xl bg-gradient-to-r from-teal-900 to-navy-900 text-white shadow-lg border border-teal-700">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-teal-400 block mb-1">
                            Aksi Validasi Operasional Admin
                        </span>
                        <h3 class="text-base font-bold text-white">Konfirmasi Pesanan & Potong Stok Cold Storage</h3>
                        <p class="text-xs text-slate-300 mt-1 max-w-xl">
                            Mengonfirmasi pesanan ini akan secara otomatis memverifikasi ketersediaan komoditas di cold storage, memotong kuantitas stok secara real-time, menerbitkan catatan transaksi keuangan, dan menyiapkan manifes pengiriman armada.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('pemesanans.confirm', $pemesanan) }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="btn-primary bg-teal-500 hover:bg-teal-400 text-navy-950 font-bold text-xs py-3 px-5 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Konfirmasi & Potong Stok</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Client & Manifest Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Information Card -->
            <div class="card p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Pelanggan B2B & Lokasi Kirim</span>
                </h3>
                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-400 block">Nama Perusahaan / Mitra:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $pemesanan->nama_pelanggan }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Kontak PIC / WhatsApp:</span>
                        <span class="font-medium text-slate-800">{{ $pemesanan->no_telp ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Alamat Tujuan Cold Storage:</span>
                        <span class="font-medium text-slate-800">{{ $pemesanan->alamat_tujuan }}</span>
                    </div>
                    @if ($pemesanan->catatan)
                        <div class="pt-1">
                            <span class="text-slate-400 block">Instruksi Khusus:</span>
                            <span class="italic text-slate-700 bg-slate-50 p-2 rounded block mt-0.5">{{ $pemesanan->catatan }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Status & Timeline Card -->
            <div class="card p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Metadata & Status Pemesanan</span>
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Tanggal Input PO:</span>
                        <span class="font-semibold text-slate-800">{{ $pemesanan->tgl_pesan ? $pemesanan->tgl_pesan->format('d F Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Petugas Pembuat:</span>
                        <span class="font-semibold text-slate-800">{{ $pemesanan->user->name ?? $pemesanan->user->username ?? 'Sistem' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Status Pembukuan:</span>
                        @if ($pemesanan->transaksi)
                            <a href="{{ route('transaksis.show', $pemesanan->transaksi) }}" class="font-bold text-teal-700 hover:underline">
                                Terbit Transaksi #TRX-{{ str_pad($pemesanan->transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        @else
                            <span class="text-slate-400 italic">Belum dibukukan (Menunggu konfirmasi)</span>
                        @endif
                    </div>

                    @if (auth()->user()->isAdmin() && in_array($pemesanan->status_pesan, ['diproses', 'dikirim', 'selesai']))
                        <div class="pt-3 border-t border-slate-100">
                            <form method="POST" action="{{ route('pemesanans.status', $pemesanan) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status_pesan" class="input-field text-xs py-1.5">
                                    <option value="diproses" {{ $pemesanan->status_pesan === 'diproses' ? 'selected' : '' }}>Status: Diproses</option>
                                    <option value="dikirim" {{ $pemesanan->status_pesan === 'dikirim' ? 'selected' : '' }}>Status: Dikirim</option>
                                    <option value="selesai" {{ $pemesanan->status_pesan === 'selesai' ? 'selected' : '' }}>Status: Selesai</option>
                                </select>
                                <button type="submit" class="btn-secondary text-xs py-1.5 px-3">
                                    Update
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ordered Items Table Card -->
        <div class="card overflow-hidden">
            <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Rincian Komoditas Dipesan</h3>
                <span class="text-xs text-slate-500 font-medium">{{ $pemesanan->items->count() }} Komoditas</span>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Komoditas Ikan</th>
                            <th>Kategori</th>
                            <th>Kuantitas</th>
                            <th>Harga Satuan (IDR)</th>
                            <th>Subtotal (IDR)</th>
                            <th>Status Stok Cold Storage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pemesanan->items as $idx => $item)
                            @php
                                $stokReady = $item->ikan->stok ? $item->ikan->stok->jumlah_stok : 0;
                            @endphp
                            <tr>
                                <td class="text-xs text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="font-bold text-slate-900">{{ $item->ikan->nama_ikan }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-slate-100 text-slate-700">{{ $item->ikan->jenis_ikan }}</span>
                                </td>
                                <td class="font-semibold text-slate-800">
                                    {{ number_format($item->jumlah, 1) }} {{ $item->ikan->satuan }}
                                </td>
                                <td class="text-xs text-slate-600">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="font-bold text-slate-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($pemesanan->status_pesan === 'pending')
                                        @if ($stokReady >= $item->jumlah)
                                            <span class="badge bg-emerald-100 text-emerald-800 font-semibold">
                                                Tersedia ({{ number_format($stokReady, 1) }} {{ $item->ikan->satuan }})
                                            </span>
                                        @else
                                            <span class="badge bg-rose-100 text-rose-700 font-bold">
                                                Kurang! (Hanya {{ number_format($stokReady, 1) }} {{ $item->ikan->satuan }})
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-slate-100 text-slate-600">
                                            Telah Terpotong
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Bar -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Total Nilai Pesanan Grosir</span>
                <div class="text-xl font-black text-slate-900">
                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Fleet Logistics Delivery Card -->
        @if ($pemesanan->pengiriman)
            <div class="card p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        <span>Manifes Pengiriman Armada Cold Storage</span>
                    </h3>
                    <a href="{{ route('pengirimans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900">
                        Ke Modul Armada &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs mb-4">
                    <div>
                        <span class="text-slate-400 block">Armada & Pengemudi:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $pemesanan->pengiriman->nama_kurir_armada ?? 'Belum Ditugaskan' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Waktu Berangkat / Kirim:</span>
                        <span class="font-semibold text-slate-800">{{ $pemesanan->pengiriman->tgl_kirim ? $pemesanan->pengiriman->tgl_kirim->format('d/m/Y H:i') : 'Menunggu Dispatch' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Waktu Tiba di Tujuan:</span>
                        <span class="font-semibold text-slate-800">{{ $pemesanan->pengiriman->tgl_diterima ? $pemesanan->pengiriman->tgl_diterima->format('d/m/Y H:i') : 'Dalam Proses' }}</span>
                    </div>
                </div>

                <!-- Delivery Visual Timeline -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2">
                        <span class="{{ in_array($pemesanan->pengiriman->status_kirim, ['siap_dikirim', 'dalam_perjalanan', 'terkirim']) ? 'text-teal-700 font-bold' : '' }}">
                            1. Siap Muat Cold Storage
                        </span>
                        <span class="{{ in_array($pemesanan->pengiriman->status_kirim, ['dalam_perjalanan', 'terkirim']) ? 'text-indigo-700 font-bold' : '' }}">
                            2. Dalam Perjalanan Armada
                        </span>
                        <span class="{{ $pemesanan->pengiriman->status_kirim === 'terkirim' ? 'text-emerald-700 font-bold' : '' }}">
                            3. Sampai di Pelanggan (Selesai)
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        @php
                            $progress = match($pemesanan->pengiriman->status_kirim) {
                                'siap_dikirim' => '33%',
                                'dalam_perjalanan' => '66%',
                                'terkirim' => '100%',
                                default => '0%'
                            };
                        @endphp
                        <div class="bg-gradient-to-r from-teal-500 to-emerald-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

