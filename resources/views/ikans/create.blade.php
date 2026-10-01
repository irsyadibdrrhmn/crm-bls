<x-app-layout>
    <x-slot name="headerTitle">Tambah Komoditas Ikan Baru</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <a href="{{ route('ikans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Master Data
                </a>
                <h2 class="text-lg font-bold text-slate-900">Tambah Data Komoditas Ikan</h2>
            </div>
        </div>

        <div class="card p-6 sm:p-7">
            <form method="POST" action="{{ route('ikans.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="nama_ikan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Komoditas Ikan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nama_ikan"
                           name="nama_ikan"
                           value="{{ old('nama_ikan') }}"
                           placeholder="contoh: Ikan Tuna Yellowfin Grade A"
                           required
                           class="input-field">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_ikan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis / Kategori Tangkapan <span class="text-rose-500">*</span>
                        </label>
                        <select id="jenis_ikan" name="jenis_ikan" required class="input-field">
                            <option value="">Pilih Kategori...</option>
                            <option value="Pelagis" {{ old('jenis_ikan') === 'Pelagis' ? 'selected' : '' }}>Pelagis (Cakalang, Tongkol, Tenggiri)</option>
                            <option value="Pelagis Besar" {{ old('jenis_ikan') === 'Pelagis Besar' ? 'selected' : '' }}>Pelagis Besar (Tuna, Marlin)</option>
                            <option value="Demersal" {{ old('jenis_ikan') === 'Demersal' ? 'selected' : '' }}>Demersal (Kakap, Kerapu, Bawal)</option>
                            <option value="Cephalopoda" {{ old('jenis_ikan') === 'Cephalopoda' ? 'selected' : '' }}>Cephalopoda (Cumi-Cumi, Gurita, Sotong)</option>
                            <option value="Crustacea" {{ old('jenis_ikan') === 'Crustacea' ? 'selected' : '' }}>Crustacea (Udang, Kepiting, Lobster)</option>
                            <option value="Air Tawar & Payau" {{ old('jenis_ikan') === 'Air Tawar & Payau' ? 'selected' : '' }}>Air Tawar & Payau (Bandeng, Gurame)</option>
                            <option value="Import Salmonidae" {{ old('jenis_ikan') === 'Import Salmonidae' ? 'selected' : '' }}>Import Salmonidae (Salmon, Trout)</option>
                        </select>
                    </div>

                    <div>
                        <label for="satuan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Satuan Distribusi Grosir <span class="text-rose-500">*</span>
                        </label>
                        <select id="satuan" name="satuan" required class="input-field">
                            <option value="Kg" {{ old('satuan') === 'Kg' ? 'selected' : '' }}>Kg (Kilogram)</option>
                            <option value="Box (25 Kg)" {{ old('satuan') === 'Box (25 Kg)' ? 'selected' : '' }}>Box (25 Kg)</option>
                            <option value="Ton" {{ old('satuan') === 'Ton' ? 'selected' : '' }}>Ton (1.000 Kg)</option>
                            <option value="Keranjang" {{ old('satuan') === 'Keranjang' ? 'selected' : '' }}>Keranjang (50 Kg)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="harga_jual" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Harga Jual Grosir (IDR) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-semibold pointer-events-none">Rp</span>
                            <input type="number"
                                   id="harga_jual"
                                   name="harga_jual"
                                   value="{{ old('harga_jual') }}"
                                   placeholder="85000"
                                   step="500"
                                   min="0"
                                   required
                                   class="input-field pl-10">
                        </div>
                    </div>

                    <div>
                        <label for="initial_stok" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Stok Awal di Cold Storage (Kg)
                        </label>
                        <input type="number"
                               id="initial_stok"
                               name="initial_stok"
                               value="{{ old('initial_stok', 0) }}"
                               placeholder="500"
                               step="0.5"
                               min="0"
                               class="input-field">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('ikans.index') }}" class="btn-secondary text-xs">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary text-xs px-5">
                        Simpan Komoditas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

