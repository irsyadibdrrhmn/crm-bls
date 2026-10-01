<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use App\Models\Pemesanan;
use App\Models\Pengiriman;
use App\Models\Stok;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Financial & Volume Metrics
        $totalRevenue = Transaksi::join('pemesanans', 'transaksis.id_pemesanan', '=', 'pemesanans.id_pemesanan')
            ->sum('pemesanans.total_harga');

        $thisMonthRevenue = Transaksi::join('pemesanans', 'transaksis.id_pemesanan', '=', 'pemesanans.id_pemesanan')
            ->whereMonth('transaksis.tanggal_transaksi', Carbon::now()->month)
            ->whereYear('transaksis.tanggal_transaksi', Carbon::now()->year)
            ->sum('pemesanans.total_harga');

        $totalStockKg = Stok::sum('jumlah_stok');
        $lowStockCount = Stok::where('jumlah_stok', '<', 250)->count();

        // Operational Counts
        $activeOrdersCount = Pemesanan::whereIn('status_pesan', ['pending', 'diproses', 'dikirim'])->count();
        $pendingApprovalCount = Pemesanan::where('status_pesan', 'pending')->count();
        $inTransitDeliveriesCount = Pengiriman::where('status_kirim', 'dalam_perjalanan')->count();
        $completedDeliveriesCount = Pengiriman::where('status_kirim', 'terkirim')->count();

        // Recent Data Lists
        $recentOrders = Pemesanan::with(['items.ikan', 'transaksi', 'pengiriman'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $activeDeliveries = Pengiriman::with('pemesanan')
            ->whereIn('status_kirim', ['siap_dikirim', 'dalam_perjalanan'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $lowStockItems = Stok::with('ikan')
            ->where('jumlah_stok', '<', 300)
            ->orderBy('jumlah_stok', 'asc')
            ->take(5)
            ->get();

        // Monthly Revenue Chart Data (Last 6 months)
        $months = [];
        $revenueTrend = [];
        $orderVolumeTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $months[] = $monthName;

            $rev = Transaksi::join('pemesanans', 'transaksis.id_pemesanan', '=', 'pemesanans.id_pemesanan')
                ->whereMonth('transaksis.tanggal_transaksi', $date->month)
                ->whereYear('transaksis.tanggal_transaksi', $date->year)
                ->sum('pemesanans.total_harga');
            $revenueTrend[] = (float) $rev;

            $ordersCount = Pemesanan::whereMonth('tgl_pesan', $date->month)
                ->whereYear('tgl_pesan', $date->year)
                ->count();
            $orderVolumeTrend[] = $ordersCount;
        }

        // Category breakdown
        $categories = Ikan::select('jenis_ikan', DB::raw('count(*) as total'))
            ->groupBy('jenis_ikan')
            ->pluck('total', 'jenis_ikan');

        $categoryLabels = is_array($categories) ? array_keys($categories) : $categories->keys()->values()->all();
        $categoryValues = is_array($categories) ? array_values($categories) : $categories->values()->all();

        // Top Selling Fish
        $topFish = DB::table('pemesanan_items')
            ->join('ikans', 'pemesanan_items.id_ikan', '=', 'ikans.id_ikan')
            ->select('ikans.nama_ikan', DB::raw('SUM(pemesanan_items.jumlah) as total_kg'), DB::raw('SUM(pemesanan_items.subtotal) as total_nominal'))
            ->groupBy('ikans.nama_ikan')
            ->orderByDesc('total_kg')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalRevenue',
            'thisMonthRevenue',
            'totalStockKg',
            'lowStockCount',
            'activeOrdersCount',
            'pendingApprovalCount',
            'inTransitDeliveriesCount',
            'completedDeliveriesCount',
            'recentOrders',
            'activeDeliveries',
            'lowStockItems',
            'months',
            'revenueTrend',
            'orderVolumeTrend',
            'categories',
            'categoryLabels',
            'categoryValues',
            'topFish'
        ));
    }
}

