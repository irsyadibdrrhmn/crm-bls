<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Pengiriman;
use App\Models\Stok;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Pemesanan::with(['items.ikan', 'transaksi', 'pengiriman', 'user']);

        if ($status && in_array($status, ['pending', 'diproses', 'dikirim', 'selesai'])) {
            $query->where('status_pesan', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('id_pemesanan', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $pemesanans = $query->latest('created_at')->paginate(15)->withQueryString();

        $counts = [
            'all' => Pemesanan::count(),
            'pending' => Pemesanan::where('status_pesan', 'pending')->count(),
            'diproses' => Pemesanan::where('status_pesan', 'diproses')->count(),
            'dikirim' => Pemesanan::where('status_pesan', 'dikirim')->count(),
            'selesai' => Pemesanan::where('status_pesan', 'selesai')->count(),
        ];

        return view('pemesanans.index', compact('pemesanans', 'status', 'search', 'counts'));
    }

    public function create()
    {
        $ikans = Ikan::with('stok')->orderBy('nama_ikan', 'asc')->get();
        return view('pemesanans.create', compact('ikans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:50',
            'alamat_tujuan' => 'required|string',
            'tgl_pesan' => 'required|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id_ikan' => 'required|exists:ikans,id_ikan',
            'items.*.jumlah' => 'required|numeric|min:0.5',
        ]);

        DB::transaction(function () use ($validated) {
            $pemesanan = Pemesanan::create([
                'id_user' => auth()->id(),
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'no_telp' => $validated['no_telp'],
                'alamat_tujuan' => $validated['alamat_tujuan'],
                'tgl_pesan' => $validated['tgl_pesan'],
                'total_harga' => 0,
                'status_pesan' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $totalHarga = 0;

            foreach ($validated['items'] as $itemData) {
                $ikan = Ikan::findOrFail($itemData['id_ikan']);
                $jumlah = $itemData['jumlah'];
                $subtotal = $jumlah * $ikan->harga_jual;
                $totalHarga += $subtotal;

                PemesananItem::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_ikan' => $ikan->id_ikan,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $ikan->harga_jual,
                    'subtotal' => $subtotal,
                ]);
            }

            $pemesanan->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('pemesanans.index')->with('success', 'Pesanan B2B grosir berhasil dibuat dan menunggu konfirmasi/proses.');
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['items.ikan.stok', 'transaksi.user', 'pengiriman', 'user']);
        return view('pemesanans.show', compact('pemesanan'));
    }

    public function confirm(Pemesanan $pemesanan)
    {
        if ($pemesanan->status_pesan !== 'pending') {
            return back()->with('error', 'Hanya pesanan berstatus pending yang dapat dikonfirmasi.');
        }

        $pemesanan->load('items.ikan.stok');

        // Validasi stok
        foreach ($pemesanan->items as $item) {
            $stok = $item->ikan->stok;
            $stokTersedia = $stok ? $stok->jumlah_stok : 0;
            if ($stokTersedia < $item->jumlah) {
                return back()->with('error', "Stok untuk {$item->ikan->nama_ikan} tidak mencukupi (Tersedia: {$stokTersedia} {$item->ikan->satuan}, Dibutuhkan: {$item->jumlah} {$item->ikan->satuan}).");
            }
        }

        DB::transaction(function () use ($pemesanan) {
            $listItems = [];

            // Potong stok
            foreach ($pemesanan->items as $item) {
                $stok = Stok::where('id_ikan', $item->id_ikan)->lockForUpdate()->first();
                if ($stok) {
                    $stok->jumlah_stok -= $item->jumlah;
                    $stok->tanggal_update = Carbon::now();
                    $stok->save();
                }

                $listItems[] = "{$item->ikan->nama_ikan} ({$item->jumlah} {$item->ikan->satuan} @ Rp " . number_format($item->harga_satuan, 0, ',', '.') . ")";
            }

            // Update status pesanan
            $pemesanan->update(['status_pesan' => 'diproses']);

            // Catat Transaksi
            Transaksi::firstOrCreate(
                ['id_pemesanan' => $pemesanan->id_pemesanan],
                [
                    'tanggal_transaksi' => Carbon::now(),
                    'list_item' => implode(', ', $listItems),
                    'id_user' => auth()->id(),
                ]
            );

            // Inisialisasi tiket pengiriman armada internal
            Pengiriman::firstOrCreate(
                ['id_pemesanan' => $pemesanan->id_pemesanan],
                [
                    'nama_kurir_armada' => 'Belum Ditugaskan',
                    'status_kirim' => 'siap_dikirim',
                    'catatan_pengiriman' => 'Menunggu penjadwalan armada cold storage.',
                ]
            );
        });

        return redirect()->route('pemesanans.show', $pemesanan)->with('success', 'Pesanan berhasil dikonfirmasi! Stok ikan otomatis terpotong dan manifes pengiriman telah dibuat.');
    }

    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $validated = $request->validate([
            'status_pesan' => 'required|in:diproses,dikirim,selesai',
        ]);

        $pemesanan->update(['status_pesan' => $validated['status_pesan']]);

        // Sinkronisasi status pengiriman jika ada
        if ($pemesanan->pengiriman) {
            if ($validated['status_pesan'] === 'dikirim' && $pemesanan->pengiriman->status_kirim === 'siap_dikirim') {
                $pemesanan->pengiriman->update([
                    'status_kirim' => 'dalam_perjalanan',
                    'tgl_kirim' => Carbon::now(),
                ]);
            } elseif ($validated['status_pesan'] === 'selesai' && $pemesanan->pengiriman->status_kirim !== 'terkirim') {
                $pemesanan->pengiriman->update([
                    'status_kirim' => 'terkirim',
                    'tgl_diterima' => Carbon::now(),
                ]);
            }
        }

        return back()->with('success', "Status pesanan #{$pemesanan->id_pemesanan} berhasil diperbarui menjadi {$validated['status_pesan']}.");
    }

    public function destroy(Pemesanan $pemesanan)
    {
        if ($pemesanan->status_pesan !== 'pending') {
            return back()->with('error', 'Pesanan yang sudah diproses atau berjalan tidak dapat dihapus.');
        }

        $pemesanan->delete();

        return redirect()->route('pemesanans.index')->with('success', 'Pesanan pending berhasil dibatalkan.');
    }
}
