<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IkanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Ikan::with('stok');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_ikan', 'like', "%{$search}%")
                  ->orWhere('jenis_ikan', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('jenis_ikan', $kategori);
        }

        $ikans = $query->orderBy('nama_ikan', 'asc')->paginate(15)->withQueryString();

        $categories = Ikan::distinct()->pluck('jenis_ikan');

        return view('ikans.index', compact('ikans', 'search', 'kategori', 'categories'));
    }

    public function create()
    {
        return view('ikans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'jenis_ikan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
            'initial_stok' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $ikan = Ikan::create([
                'nama_ikan' => $validated['nama_ikan'],
                'jenis_ikan' => $validated['jenis_ikan'],
                'satuan' => $validated['satuan'],
                'harga_jual' => $validated['harga_jual'],
            ]);

            Stok::create([
                'id_ikan' => $ikan->id_ikan,
                'jumlah_stok' => $validated['initial_stok'] ?? 0,
                'tanggal_update' => Carbon::now(),
            ]);
        });

        return redirect()->route('ikans.index')->with('success', 'Master data komoditas ikan berhasil ditambahkan.');
    }

    public function edit(Ikan $ikan)
    {
        $ikan->load('stok');
        return view('ikans.edit', compact('ikan'));
    }

    public function update(Request $request, Ikan $ikan)
    {
        $validated = $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'jenis_ikan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $ikan->update($validated);

        return redirect()->route('ikans.index')->with('success', 'Data komoditas ikan berhasil diperbarui.');
    }

    public function destroy(Ikan $ikan)
    {
        if ($ikan->pemesananItems()->exists()) {
            return redirect()->route('ikans.index')->with('error', 'Komoditas tidak dapat dihapus karena tercatat dalam riwayat pesanan grosir.');
        }

        DB::transaction(function () use ($ikan) {
            $ikan->stok()->delete();
            $ikan->delete();
        });

        return redirect()->route('ikans.index')->with('success', 'Komoditas ikan berhasil dihapus dari sistem.');
    }
}

