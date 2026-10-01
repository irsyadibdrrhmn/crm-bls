<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // all, aman, menipis, kritis

        $query = Stok::with('ikan');

        if ($search) {
            $query->whereHas('ikan', function ($q) use ($search) {
                $q->where('nama_ikan', 'like', "%{$search}%")
                  ->orWhere('jenis_ikan', 'like', "%{$search}%");
            });
        }

        if ($status === 'kritis') {
            $query->where('jumlah_stok', '<', 100);
        } elseif ($status === 'menipis') {
            $query->whereBetween('jumlah_stok', [100, 250]);
        } elseif ($status === 'aman') {
            $query->where('jumlah_stok', '>', 250);
        }

        $stoks = $query->paginate(15)->withQueryString();

        $totalStok = Stok::sum('jumlah_stok');
        $countKritis = Stok::where('jumlah_stok', '<', 100)->count();
        $countMenipis = Stok::whereBetween('jumlah_stok', [100, 250])->count();
        $countAman = Stok::where('jumlah_stok', '>', 250)->count();

        return view('stoks.index', compact('stoks', 'search', 'status', 'totalStok', 'countKritis', 'countMenipis', 'countAman'));
    }

    public function update(Request $request, Stok $stok)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:tambah,koreksi',
            'jumlah' => 'required|numeric|min:0.1',
        ]);

        if ($validated['tipe'] === 'tambah') {
            $stok->jumlah_stok += $validated['jumlah'];
        } else {
            $stok->jumlah_stok = $validated['jumlah'];
        }

        $stok->tanggal_update = Carbon::now();
        $stok->save();

        return redirect()->route('stoks.index')->with('success', "Stok {$stok->ikan->nama_ikan} berhasil diperbarui (Tersedia: {$stok->jumlah_stok} {$stok->ikan->satuan}).");
    }
}

