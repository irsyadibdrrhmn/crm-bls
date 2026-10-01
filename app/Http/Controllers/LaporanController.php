<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));

        $transaksis = Transaksi::with(['pemesanan.items.ikan', 'user'])
            ->whereDate('tanggal_transaksi', '>=', $dariTanggal)
            ->whereDate('tanggal_transaksi', '<=', $sampaiTanggal)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalPendapatan = $transaksis->sum(function ($t) {
            return $t->pemesanan ? $t->pemesanan->total_harga : 0;
        });

        $totalVolumeKg = PemesananItem::whereHas('pemesanan.transaksi', function ($q) use ($dariTanggal, $sampaiTanggal) {
            $q->whereDate('tanggal_transaksi', '>=', $dariTanggal)
              ->whereDate('tanggal_transaksi', '<=', $sampaiTanggal);
        })->sum('jumlah');

        $totalTransaksi = $transaksis->count();

        return view('laporan.index', compact(
            'transaksis',
            'dariTanggal',
            'sampaiTanggal',
            'totalPendapatan',
            'totalVolumeKg',
            'totalTransaksi'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $dariTanggal = $request->input('dari_tanggal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));

        $transaksis = Transaksi::with(['pemesanan.items.ikan', 'user'])
            ->whereDate('tanggal_transaksi', '>=', $dariTanggal)
            ->whereDate('tanggal_transaksi', '<=', $sampaiTanggal)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $fileName = 'Laporan_Penjualan_BLS_' . $dariTanggal . '_sd_' . $sampaiTanggal . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transaksis) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for MS Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'ID Transaksi',
                'No PO / Pesanan',
                'Tanggal Transaksi',
                'Nama Pelanggan B2B',
                'Rincian Komoditas (List Item)',
                'Total Nominal (IDR)',
                'Status Pesanan',
                'Petugas Admin Operasional',
            ]);

            foreach ($transaksis as $t) {
                fputcsv($handle, [
                    'TRX-' . str_pad($t->id_transaksi, 5, '0', STR_PAD_LEFT),
                    'PO-' . str_pad($t->id_pemesanan, 5, '0', STR_PAD_LEFT),
                    $t->tanggal_transaksi ? $t->tanggal_transaksi->format('d/m/Y H:i') : '-',
                    $t->pemesanan ? $t->pemesanan->nama_pelanggan : '-',
                    $t->list_item,
                    $t->pemesanan ? (float) $t->pemesanan->total_harga : 0,
                    $t->pemesanan ? ucfirst($t->pemesanan->status_pesan) : '-',
                    $t->user ? $t->user->name ?? $t->user->username : 'Admin BLS',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampaiTanggal = $request->input('sampai_tanggal', Carbon::now()->format('Y-m-d'));

        $transaksis = Transaksi::with(['pemesanan.items.ikan', 'user'])
            ->whereDate('tanggal_transaksi', '>=', $dariTanggal)
            ->whereDate('tanggal_transaksi', '<=', $sampaiTanggal)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $totalPendapatan = $transaksis->sum(function ($t) {
            return $t->pemesanan ? $t->pemesanan->total_harga : 0;
        });

        $totalVolumeKg = PemesananItem::whereHas('pemesanan.transaksi', function ($q) use ($dariTanggal, $sampaiTanggal) {
            $q->whereDate('tanggal_transaksi', '>=', $dariTanggal)
              ->whereDate('tanggal_transaksi', '<=', $sampaiTanggal);
        })->sum('jumlah');

        return view('laporan.print', compact(
            'transaksis',
            'dariTanggal',
            'sampaiTanggal',
            'totalPendapatan',
            'totalVolumeKg'
        ));
    }
}

