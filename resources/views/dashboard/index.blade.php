<x-app-layout>
    <x-slot name="headerTitle">
        {{ $user->role === 'owner' ? 'Executive Oversight & Analytics Dashboard' : 'Operational Command & Distribution Dashboard' }}
    </x-slot>

    <!-- Top Role Context & Quick Action Banner -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $user->role === 'owner' ? 'bg-amber-500/10 text-amber-600' : 'bg-teal-500/10 text-teal-600' }} flex items-center justify-center shrink-0">
                @if ($user->role === 'owner')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                @endif
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Selamat datang, {{ $user->name ?? $user->username }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    @if ($user->role === 'owner')
                        Ringkasan eksekutif penjualan ikan laut grosir, perputaran stok cold storage, dan performa armada logistik CV Bakri Laut Sejahtera.
                    @else
                        Pusat kendali operasional harian: validasi pesanan B2B, pemotongan stok otomatis, dan penjadwalan armada cold chain.
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            @if ($user->role === 'admin')
                <a href="{{ route('pemesanans.create') }}" class="btn-primary text-xs py-2 px-3.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Input Pesanan Grosir</span>
                </a>
                <a href="{{ route('ikans.create') }}" class="btn-secondary text-xs py-2 px-3.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Tambah Komoditas</span>
                </a>
            @else
                <a href="{{ route('laporan.index') }}" class="btn-primary text-xs py-2 px-3.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Lihat Laporan Penjualan</span>
                </a>
                <a href="{{ route('laporan.export.csv') }}" class="btn-secondary text-xs py-2 px-3.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Ekspor CSV / Excel</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Analytical Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Revenue -->
        <div class="card p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pendapatan</span>
                <span class="p-2 rounded-lg bg-teal-50 text-teal-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="mt-1 flex items-center text-xs text-teal-700">
                    <span class="font-medium">Bulan ini: Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Cold Storage Stock Level -->
        <div class="card p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Cold Storage</span>
                <span class="p-2 rounded-lg bg-sky-50 text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ number_format($totalStockKg, 1, ',', '.') }} <span class="text-sm font-semibold text-slate-500">Kg</span>
                </div>
                <div class="mt-1 flex items-center text-xs {{ $lowStockCount > 0 ? 'text-rose-600 font-medium' : 'text-slate-500' }}">
                    @if ($lowStockCount > 0)
                        <span>&bull; {{ $lowStockCount }} komoditas stok menipis</span>
                    @else
                        <span>&bull; Semua komoditas dalam ambang aman</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card 3: Active Wholesale Orders -->
        <div class="card p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pesanan B2B Aktif</span>
                <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ $activeOrdersCount }} <span class="text-sm font-semibold text-slate-500">Order</span>
                </div>
                <div class="mt-1 flex items-center text-xs text-amber-700 font-medium">
                    <span>{{ $pendingApprovalCount }} menunggu validasi admin</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Fleet Logistics Status -->
        <div class="card p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Armada</span>
                <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ $inTransitDeliveriesCount }} <span class="text-sm font-semibold text-slate-500">Dalam Perjalanan</span>
                </div>
                <div class="mt-1 flex items-center text-xs text-emerald-700 font-medium">
                    <span>{{ $completedDeliveriesCount }} pengiriman sukses terselesaikan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Sales & Revenue Trend Chart -->
        <div class="card p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Tren Penjualan & Pendapatan Grosir</h3>
                    <p class="text-xs text-slate-500">Performa transaksi nominal 6 bulan terakhir</p>
                </div>
                <span class="badge bg-teal-100 text-teal-800">B2B Sales</span>
            </div>
            <div class="h-64 sm:h-72 relative w-full">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Fish Category Distribution -->
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kategori Hasil Laut</h3>
                    <p class="text-xs text-slate-500">Distribusi varietas komoditas</p>
                </div>
                <span class="badge bg-navy-100 text-navy-800">Katalog</span>
            </div>
            <div class="h-64 sm:h-72 relative w-full">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables & Operational Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders (2 cols) -->
        <div class="card p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Pesanan Grosir B2B Terbaru</h3>
                    <p class="text-xs text-slate-500">Aktivitas permintaan komoditas ikan terkini</p>
                </div>
                <a href="{{ route('pemesanans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Pelanggan B2B</th>
                            <th>Tgl Pesan</th>
                            <th>Total (IDR)</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="font-bold text-slate-900">
                                    PO-{{ str_pad($order->id_pemesanan, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $order->nama_pelanggan }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $order->items->count() }} komoditas</div>
                                </td>
                                <td class="text-xs text-slate-600">
                                    {{ $order->tgl_pesan ? $order->tgl_pesan->format('d/m/Y') : '-' }}
                                </td>
                                <td class="font-bold text-slate-900">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($order->status_pesan === 'pending')
                                        <span class="badge bg-amber-100 text-amber-800">Pending</span>
                                    @elseif ($order->status_pesan === 'diproses')
                                        <span class="badge bg-sky-100 text-sky-800">Diproses</span>
                                    @elseif ($order->status_pesan === 'dikirim')
                                        <span class="badge bg-indigo-100 text-indigo-800">Dikirim</span>
                                    @else
                                        <span class="badge bg-emerald-100 text-emerald-800">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('pemesanans.show', $order) }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900">
                                        Rincian
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400 text-xs">
                                    Belum ada pesanan grosir tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Low Stock Warnings & Top Fish -->
        <div class="space-y-6">
            <!-- Low Stock Alert -->
            <div class="card p-5 border-l-4 border-rose-500">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Peringatan Stok Cold Storage</span>
                    </h3>
                    <a href="{{ route('stoks.index') }}" class="text-[11px] font-semibold text-teal-700">Audit</a>
                </div>

                <div class="space-y-3">
                    @forelse ($lowStockItems as $stok)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 text-xs">
                            <div>
                                <span class="font-bold text-slate-800">{{ $stok->ikan->nama_ikan }}</span>
                                <span class="block text-[10px] text-slate-500">{{ $stok->ikan->jenis_ikan }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-rose-600">{{ number_format($stok->jumlah_stok, 1) }} {{ $stok->ikan->satuan }}</span>
                                <span class="block text-[10px] text-slate-400">Tersisa</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2">Semua stok berada dalam ambang aman operasional.</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="card p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Komoditas Terlaris</h3>
                <div class="space-y-2.5">
                    @forelse ($topFish as $fish)
                        <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-100 last:border-0">
                            <span class="font-medium text-slate-700 truncate max-w-[140px]">{{ $fish->nama_ikan }}</span>
                            <div class="text-right">
                                <span class="font-bold text-slate-900">{{ number_format($fish->total_kg, 0) }} Kg</span>
                                <span class="block text-[10px] text-slate-400">Rp {{ number_format($fish->total_nominal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-2">Belum ada data akumulasi penjualan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            let retries = 0;
            const maxRetries = 50;

            function initCharts() {
                if (typeof window.Chart === 'undefined') {
                    if (retries < maxRetries) {
                        retries++;
                        setTimeout(initCharts, 50);
                    }
                    return;
                }

                // 1. Sales & Revenue Trend Chart
                const salesCanvas = document.getElementById('salesTrendChart');
                if (salesCanvas && !salesCanvas.dataset.initialized) {
                    salesCanvas.dataset.initialized = 'true';
                    const salesCtx = salesCanvas.getContext('2d');
                    new Chart(salesCtx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($months) !!},
                            datasets: [
                                {
                                    label: 'Pendapatan (IDR)',
                                    data: {!! json_encode($revenueTrend) !!},
                                    backgroundColor: 'rgba(13, 148, 136, 0.85)',
                                    hoverBackgroundColor: 'rgb(13, 148, 136)',
                                    borderColor: 'rgb(13, 148, 136)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    yAxisID: 'y',
                                    order: 2
                                },
                                {
                                    label: 'Jumlah Order Selesai',
                                    data: {!! json_encode($orderVolumeTrend) !!},
                                    type: 'line',
                                    borderColor: 'rgb(15, 23, 42)',
                                    backgroundColor: 'rgba(15, 23, 42, 0.1)',
                                    borderWidth: 2.5,
                                    tension: 0.35,
                                    yAxisID: 'y1',
                                    pointRadius: 4.5,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: 'rgb(15, 23, 42)',
                                    order: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + (value / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 0 }) + ' Jt';
                                        },
                                        font: { size: 11, family: 'Inter' }
                                    },
                                    grid: {
                                        color: '#f1f5f9'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        font: { size: 11, family: 'Inter' }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: { size: 11, family: 'Inter' }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        boxWidth: 12,
                                        font: { size: 11, family: 'Inter', weight: 'bold' }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.dataset.yAxisID === 'y') {
                                                return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            }
                                            return 'Pesanan: ' + context.parsed.y + ' Order';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // 2. Fish Category Doughnut Chart
                const catCanvas = document.getElementById('categoryChart');
                if (catCanvas && !catCanvas.dataset.initialized) {
                    catCanvas.dataset.initialized = 'true';
                    const catCtx = catCanvas.getContext('2d');
                    const catLabels = {!! json_encode($categoryLabels ?? []) !!};
                    const catValues = {!! json_encode($categoryValues ?? []) !!};

                    new Chart(catCtx, {
                        type: 'doughnut',
                        data: {
                            labels: catLabels,
                            datasets: [{
                                data: catValues,
                                backgroundColor: [
                                    '#0d9488', // Teal
                                    '#0284c7', // Sky
                                    '#2563eb', // Blue
                                    '#4f46e5', // Indigo
                                    '#0f172a', // Navy
                                    '#059669', // Emerald
                                    '#d97706', // Amber
                                    '#7c3aed'  // Purple
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        padding: 12,
                                        font: { size: 10.5, family: 'Inter' }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return ' ' + context.label + ': ' + context.parsed + ' Varietas';
                                        }
                                    }
                                }
                            },
                            cutout: '62%'
                        }
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCharts);
            } else {
                initCharts();
            }
        })();
    </script>
    @endpush
</x-app-layout>

