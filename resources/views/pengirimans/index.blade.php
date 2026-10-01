<x-app-layout>
    <x-slot name="headerTitle">Logistik & Pelacakan Armada Pengiriman Internal</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen Armada Truk Cold Storage & Pickup Thermoking</h2>
            <p class="text-xs text-slate-500 mt-1">Pantau pergerakan pengiriman hasil laut segar ke mitra distributor, hotel, dan restoran.</p>
        </div>
    </div>

    <!-- Status Tabs & Search Filter Card -->
    <div class="card p-4 sm:p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex flex-wrap gap-1.5">
                @php
                    $tabs = [
                        ['id' => '', 'label' => 'Semua Armada', 'count' => $counts['all']],
                        ['id' => 'siap_dikirim', 'label' => 'Siap Dikirim', 'count' => $counts['siap_dikirim']],
                        ['id' => 'dalam_perjalanan', 'label' => 'Dalam Perjalanan', 'count' => $counts['dalam_perjalanan']],
                        ['id' => 'terkirim', 'label' => 'Terkirim', 'count' => $counts['terkirim']],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    @php($isCurrent = $status === $tab['id'])
                    <a href="{{ route('pengirimans.index', array_merge(request()->query(), ['status' => $tab['id']])) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-150 {{ $isCurrent ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>{{ $tab['label'] }}</span>
                        <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $isCurrent ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('pengirimans.index') }}" class="flex items-center gap-2">
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
                           placeholder="Cari armada, driver, atau PO..."
                           class="input-field pl-10 text-xs">
                </div>
                <button type="submit" class="btn-secondary text-xs px-4 py-2.5 shrink-0">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('pengirimans.index', ['status' => $status]) }}" class="btn-outline text-xs px-2.5 py-2.5 text-slate-400 hover:text-slate-600">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Logistics Delivery Table Card -->
    <div class="card" x-data="{ dispatchModalOpen: false, currentDelivery: null }">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-20">ID Tiket</th>
                        <th>No. PO & Pelanggan B2B</th>
                        <th>Alamat Tujuan Distribusi</th>
                        <th>Armada Kendaraan & Driver</th>
                        <th>Waktu Dispatch / Tiba</th>
                        <th>Status Armada</th>
                        @if (auth()->user()->isAdmin())
                            <th class="text-right">Aksi Dispatch</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengirimans as $kirim)
                        <tr>
                            <td class="font-mono text-xs font-semibold text-slate-400">
                                #DLV-{{ str_pad($kirim->id_pengiriman, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <a href="{{ route('pemesanans.show', $kirim->id_pemesanan) }}" class="font-mono font-bold text-teal-700 hover:underline text-xs">
                                    PO-{{ str_pad($kirim->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                                </a>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">
                                    {{ $kirim->pemesanan?->nama_pelanggan }}
                                </div>
                            </td>
                            <td class="text-xs text-slate-600 max-w-xs truncate" title="{{ $kirim->pemesanan?->alamat_tujuan }}">
                                {{ $kirim->pemesanan?->alamat_tujuan }}
                            </td>
                            <td>
                                @if ($kirim->nama_kurir_armada && $kirim->nama_kurir_armada !== 'Belum Ditugaskan')
                                    <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                        <span class="truncate max-w-[200px]">{{ $kirim->nama_kurir_armada }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-slate">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">
                                @if ($kirim->tgl_kirim)
                                    <div>Berangkat: <span class="font-semibold text-slate-800">{{ $kirim->tgl_kirim->format('d/m H:i') }}</span></div>
                                @endif
                                @if ($kirim->tgl_diterima)
                                    <div class="mt-0.5">Sampai: <span class="font-semibold text-emerald-700">{{ $kirim->tgl_diterima->format('d/m H:i') }}</span></div>
                                @endif
                                @if (!$kirim->tgl_kirim && !$kirim->tgl_diterima)
                                    <span class="text-slate-400 italic">Menunggu muat</span>
                                @endif
                            </td>
                            <td>
                                @if ($kirim->status_kirim === 'siap_dikirim')
                                    <span class="badge badge-amber">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Siap Dikirim
                                    </span>
                                @elseif ($kirim->status_kirim === 'dalam_perjalanan')
                                    <span class="badge badge-indigo">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                        Dalam Perjalanan
                                    </span>
                                @else
                                    <span class="badge badge-emerald">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Terkirim
                                    </span>
                                @endif
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td class="text-right">
                                    <button type="button"
                                            @click="currentDelivery = {{ json_encode([
                                                'id' => $kirim->id_pengiriman,
                                                'id_po' => $kirim->id_pemesanan,
                                                'pelanggan' => $kirim->pemesanan?->nama_pelanggan,
                                                'kurir' => $kirim->nama_kurir_armada,
                                                'status' => $kirim->status_kirim,
                                                'catatan' => $kirim->catatan_pengiriman,
                                                'update_url' => route('pengirimans.update', $kirim)
                                            ]) }}; dispatchModalOpen = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200 bg-teal-50 text-teal-700 text-xs font-semibold hover:bg-teal-600 hover:text-white hover:border-teal-600 transition shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Update Armada</span>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada tiket pengiriman ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter armada logistik Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengirimans->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $pengirimans->links() }}
            </div>
        @endif

        <!-- Dispatch Update Modal for Admin -->
        <div x-show="dispatchModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             x-transition.opacity>
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200"
                 @click.away="dispatchModalOpen = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                        <span>Update Status & Armada Cold Storage</span>
                    </h3>
                    <button @click="dispatchModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
                </div>

                <template x-if="currentDelivery">
                    <form :action="currentDelivery.update_url" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                            <span class="text-slate-500 block">Tujuan Pengiriman:</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block" x-text="currentDelivery.pelanggan"></span>
                            <div class="mt-1 font-mono text-teal-700 font-bold" x-text="'PO-' + String(currentDelivery.id_po).padStart(5, '0')"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Driver & Armada Truk <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_kurir_armada"
                                   :value="currentDelivery.kurir"
                                   required
                                   placeholder="contoh: Truck Cold 01 (B 9102 BLS) - Bpk. Bambang"
                                   class="input-field">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Status Pergerakan Logistik
                            </label>
                            <select name="status_kirim" class="input-field" :value="currentDelivery.status">
                                <option value="siap_dikirim">Siap Dikirim (Loading Muatan Cold Storage)</option>
                                <option value="dalam_perjalanan">Dalam Perjalanan (On Route / OTW)</option>
                                <option value="terkirim">Terkirim (Diterima Mitra B2B)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Catatan Pengiriman (Suhu, Segel Kontainer, dll)
                            </label>
                            <textarea name="catatan_pengiriman"
                                      rows="3"
                                      placeholder="contoh: Suhu boks -4C, segel aman, muatan 150 kg..."
                                      class="input-field"
                                      x-text="currentDelivery.catatan"></textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" @click="dispatchModalOpen = false" class="btn-outline text-xs py-2 px-3.5">
                                Batal
                            </button>
                            <button type="submit" class="btn-primary text-xs py-2 px-4 shadow-sm">
                                Simpan Update Logistik
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
