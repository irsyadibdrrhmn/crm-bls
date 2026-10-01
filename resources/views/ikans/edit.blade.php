<x-app-layout>
    <x-slot name="headerTitle">Perbarui Data Komoditas Ikan</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <a href="{{ route('ikans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Master Data
                </a>
                <h2 class="text-lg font-bold text-slate-900">Perbarui Informasi Komoditas #{{ $ikan->id_ikan }}</h2>
            </div>
        </div>

        <div class="card p-6 sm:p-7">
            <form method="POST" action="{{ route('ikans.update', $ikan) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_ikan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Komoditas Ikan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_ikan"
                           name="nama_ikan"
                           value="{{ old('nama_ikan', $ikan->nama_ikan) }}"
                           required
                           class="input-field">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_ikan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis / Kategori Tangkapan <span class="text-rose-500">*</span>
                        </label>
                        <select id="jenis_ikan" name="jenis_ikan" required class="input-field">
                            @php
                                $categories = [
                                    'Pelagis' => 'Pelagis (Cakalang, Tongkol, Tenggiri)',
                                    'Pelagis Besar' => 'Pelagis Besar (Tuna, Marlin)',
                                    'Demersal' => 'Demersal (Kakap, Kerapu, Bawal)',
                                    'Cephalopoda' => 'Cephalopoda (Cumi-Cumi, Gurita, Sotong)',
                                    'Crustacea' => 'Crustacea (Udang, Kepiting, Lobster)',
                                    'Air Tawar & Payau' => 'Air Tawar & Payau (Bandeng, Gurame)',
                                    'Import Salmonidae' => 'Import Salmonidae (Salmon, Trout)',
                                ];
                            @endphp
                            @foreach ($categories as $val => $lbl)
                                <option value="{{ $val }}" {{ old('jenis_ikan', $ikan->jenis_ikan) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="satuan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Satuan Distribusi Grosir <span class="text-rose-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required class="input-field">
                            <option value="Kg" {{ old('satuan', $ikan->satuan) === 'Kg' ? 'selected' : '' }}>Kg (Kilogram)</option>
                            <option value="Box (25 Kg)" {{ old('satuan', $ikan->satuan) === 'Box (25 Kg)' ? 'selected' : '' }}>Box (25 Kg)</option>
                            <option value="Ton" {{ old('satuan', $ikan->satuan) === 'Ton' ? 'selected' : '' }}>Ton (1.000 Kg)</option>
                            <option value="Keranjang" {{ old('satuan', $ikan->satuan) === 'Keranjang' ? 'selected' : '' }}>Keranjang (50 Kg)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="harga_jual" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Harga Jual Grosir (IDR) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-semibold pointer-events-none">Rp</span>
                        <input type="number"
                               id="harga_jual"
                               name="harga_jual"
                               value="{{ old('harga_jual', (int)$ikan->harga_jual) }}"
                               step="500"
                               min="0"
                               required
                               class="input-field pl-10">
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-700 block">Stok Cold Storage Saat Ini</span>
                        <span class="text-slate-500">Stok diperbarui via modul audit/restock stok</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-teal-700">{{ number_format($ikan->stok?->jumlah_stok ?? 0, 1) }} {{ $ikan->satuan }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('ikans.index') }}" class="btn-secondary text-xs">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-xs px-5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

