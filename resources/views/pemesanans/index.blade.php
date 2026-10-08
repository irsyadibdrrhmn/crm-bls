<x-app-layout>
    <x-slot name="headerTitle">Pesanan Grosir Hasil Laut (B2B)</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Purchase Order & Pesanan B2B</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola dan pantau pesanan grosir hotel, restoran, supermarket, dan distributor mitra.</p>
        </div>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('pemesanans.create') }}" class="btn-primary text-xs py-2.5 px-4 shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Input Pesanan Baru</span>
            </a>
        @endif
    </div>

    <!-- Status Tabs & Filter Card -->
    <div class="card p-4 sm:p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex flex-wrap gap-1.5">
                @php
                    $tabs = [
                        ['id' => '', 'label' => 'Semua', 'count' => $counts['all']],
                        ['id' => 'pending', 'label' => 'Pending', 'count' => $counts['pending']],
                        ['id' => 'diproses', 'label' => 'Diproses', 'count' => $counts['diproses']],
                        ['id' => 'dikirim', 'label' => 'Dikirim', 'count' => $counts['dikirim']],
                        ['id' => 'selesai', 'label' => 'Selesai', 'count' => $counts['selesai']],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    @php($isCurrent = $status === $tab['id'])
                    <a href="{{ route('pemesanans.index', array_merge(request()->query(), ['status' => $tab['id']])) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-150 {{ $isCurrent ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>{{ $tab['label'] }}</span>
                        <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $isCurrent ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('pemesanans.index') }}" class="flex items-center gap-2">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari No. PO / Pelanggan..."
                           class="input-field pl-10 text-xs">
                </div>
                <button type="submit" class="btn-secondary text-xs px-4 py-2.5 shrink-0">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('pemesanans.index', ['status' => $status]) }}" class="btn-outline text-xs px-2.5 py-2.5 text-slate-400 hover:text-slate-600">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Order Table Card -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-24">No. PO</th>
                        <th>Pelanggan B2B</th>
                        <th>Tgl Pesan</th>
                        <th>Rincian Komoditas</th>
                        <th>Total Nilai (IDR)</th>
                        <th>Status Order</th>
                        <th>Armada Logistik</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemesanans as $po)
                        <tr>
                            <td class="font-mono font-bold text-xs text-slate-900">
                                PO-{{ str_pad($po->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="font-bold text-slate-900 text-sm">{{ $po->nama_pelanggan }}</div>
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-0.5">
                                    <span class="font-mono text-teal-800">NIK: {{ $po->nik ?? '-' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $po->no_telp ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-xs text-slate-600 whitespace-nowrap">
                                {{ $po->tgl_pesan ? $po->tgl_pesan->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                <div class="text-xs text-slate-800 font-semibold">
                                    {{ $po->items->count() }} Jenis Komoditas
                                </div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">
                                    {{ $po->items->map(fn($i) => $i->ikan->nama_ikan . ' (' . $i->jumlah . ' ' . $i->ikan->satuan . ')')->join(', ') }}
                                </div>
                            </td>
                            <td>
                                <span class="font-extrabold text-slate-900 text-sm">
                                    Rp {{ number_format($po->total_harga, 0, ',', '.') }}
                                </span>
                                <div class="mt-0.5">
                                    @if ($po->metode_pembayaran === 'cash')
                                        <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Tunai (Cash)</span>
                                    @else
                                        <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200">Transfer {{ $po->nama_bank ?? 'Bank' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($po->status_pesan === 'pending')
                                    <span class="badge badge-amber">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @elseif ($po->status_pesan === 'diproses')
                                    <span class="badge badge-cyan">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                        Diproses
                                    </span>
                                @elseif ($po->status_pesan === 'dikirim')
                                    <span class="badge badge-indigo">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Dikirim
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($po->pengiriman)
                                    <span class="text-xs font-semibold text-slate-800 block truncate max-w-[150px]">
                                        {{ $po->pengiriman->nama_kurir_armada }}
                                    </span>
                                    <span class="text-[10px] text-teal-600 font-medium capitalize">
                                        {{ str_replace('_', ' ', $po->pengiriman->status_kirim) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('pemesanans.show', $po) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200 bg-teal-50 text-teal-700 text-xs font-semibold hover:bg-teal-600 hover:text-white hover:border-teal-600 transition shadow-2xs">
                                    <span>Lihat Manifes</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada purchase order ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter status pesanan atau buat order baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pemesanans->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $pemesanans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
