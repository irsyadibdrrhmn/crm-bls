<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');

        $query = Transaksi::with(['pemesanan.items.ikan', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_transaksi', 'like', "%{$search}%")
                  ->orWhere('list_item', 'like', "%{$search}%")
                  ->orWhereHas('pemesanan', function ($pq) use ($search) {
                      $pq->where('nama_pelanggan', 'like', "%{$search}%")
                         ->orWhere('id_pemesanan', 'like', "%{$search}%");
                  });
            });
        }

        if ($dariTanggal) {
            $query->whereDate('tanggal_transaksi', '>=', $dariTanggal);
        }

        if ($sampaiTanggal) {
            $query->whereDate('tanggal_transaksi', '<=', $sampaiTanggal);
        }

        $transaksis = $query->latest('tanggal_transaksi')->paginate(15)->withQueryString();

        $totalNominal = Transaksi::join('pemesanans', 'transaksis.id_pemesanan', '=', 'pemesanans.id_pemesanan')
            ->sum('pemesanans.total_harga');

        return view('transaksis.index', compact('transaksis', 'search', 'dariTanggal', 'sampaiTanggal', 'totalNominal'));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pemesanan.items.ikan', 'pemesanan.pengiriman', 'user']);
        return view('transaksis.show', compact('transaksi'));
    }
}

