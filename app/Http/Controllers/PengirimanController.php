<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Pengiriman::with(['pemesanan.items.ikan', 'pemesanan.transaksi']);

        if ($status && in_array($status, ['siap_dikirim', 'dalam_perjalanan', 'terkirim'])) {
            $query->where('status_kirim', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kurir_armada', 'like', "%{$search}%")
                  ->orWhereHas('pemesanan', function ($pq) use ($search) {
                      $pq->where('nama_pelanggan', 'like', "%{$search}%")
                         ->orWhere('id_pemesanan', 'like', "%{$search}%")
                         ->orWhere('alamat_tujuan', 'like', "%{$search}%");
                  });
            });
        }

        $pengirimans = $query->latest('updated_at')->paginate(15)->withQueryString();

        $counts = [
            'all' => Pengiriman::count(),
            'siap_dikirim' => Pengiriman::where('status_kirim', 'siap_dikirim')->count(),
            'dalam_perjalanan' => Pengiriman::where('status_kirim', 'dalam_perjalanan')->count(),
            'terkirim' => Pengiriman::where('status_kirim', 'terkirim')->count(),
        ];

        return view('pengirimans.index', compact('pengirimans', 'status', 'search', 'counts'));
    }

    public function update(Request $request, Pengiriman $pengiriman)
    {
        $validated = $request->validate([
            'nama_kurir_armada' => 'required|string|max:255',
            'status_kirim' => 'required|in:siap_dikirim,dalam_perjalanan,terkirim',
            'catatan_pengiriman' => 'nullable|string',
        ]);

        $updateData = [
            'nama_kurir_armada' => $validated['nama_kurir_armada'],
            'status_kirim' => $validated['status_kirim'],
            'catatan_pengiriman' => $validated['catatan_pengiriman'] ?? $pengiriman->catatan_pengiriman,
        ];

        if ($validated['status_kirim'] === 'dalam_perjalanan' && empty($pengiriman->tgl_kirim)) {
            $updateData['tgl_kirim'] = Carbon::now();
            $pengiriman->pemesanan()->update(['status_pesan' => 'dikirim']);
        } elseif ($validated['status_kirim'] === 'terkirim') {
            if (empty($pengiriman->tgl_kirim)) {
                $updateData['tgl_kirim'] = Carbon::now();
            }
            $updateData['tgl_diterima'] = Carbon::now();
            $pengiriman->pemesanan()->update(['status_pesan' => 'selesai']);
        }

        $pengiriman->update($updateData);

        return back()->with('success', "Manifes armada pengiriman #{$pengiriman->id_pengiriman} berhasil diperbarui.");
    }
}

