<x-app-layout>
    <x-slot name="headerTitle">Input Pesanan Grosir B2B Baru</x-slot>

    @php
        $ikansJson = $ikans->map(function($i) {
            return [
                'id' => $i->id_ikan,
                'nama' => $i->nama_ikan,
                'jenis' => $i->jenis_ikan,
                'satuan' => $i->satuan,
                'harga' => (float)$i->harga_jual,
                'stok' => (float)($i->stok ? $i->stok->jumlah_stok : 0)
            ];
        });
    @endphp

    <div class="max-w-4xl mx-auto"
         x-data="{
             ikansList: {{ json_encode($ikansJson) }},
             items: [
                 { id_ikan: '', jumlah: 10, harga: 0, satuan: 'Kg', stok: 0 }
             ],
             addItem() {
                 this.items.push({ id_ikan: '', jumlah: 10, harga: 0, satuan: 'Kg', stok: 0 });
             },
             removeItem(index) {
                 if (this.items.length > 1) {
                     this.items.splice(index, 1);
                 }
             },
             onFishChange(index) {
                 const selectedId = this.items[index].id_ikan;
                 const fish = this.ikansList.find(f => f.id == selectedId);
                 if (fish) {
                     this.items[index].harga = fish.harga;
                     this.items[index].satuan = fish.satuan;
                     this.items[index].stok = fish.stok;
                 } else {
                     this.items[index].harga = 0;
                     this.items[index].satuan = 'Kg';
                     this.items[index].stok = 0;
                 }
             },
             calcSubtotal(item) {
                 return (item.jumlah || 0) * (item.harga || 0);
             },
             get totalHarga() {
                 return this.items.reduce((acc, item) => acc + this.calcSubtotal(item), 0);
             },
             get totalQty() {
                 return this.items.reduce((acc, item) => acc + (parseFloat(item.jumlah) || 0), 0);
             }
         }">

        <div class="mb-5 flex items-center justify-between">
            <div>
                <a href="{{ route('pemesanans.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 flex items-center gap-1 mb-1">
                    &larr; Kembali ke Daftar Pesanan
                </a>
                <h2 class="text-lg font-bold text-slate-900">Formulir Purchase Order Grosir</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('pemesanans.store') }}" class="space-y-6">
            @csrf

            <!-- Client & Delivery Details Card -->
            <div class="card p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Informasi Mitra Pelanggan B2B</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_pelanggan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Perusahaan / Restoran / Mitra <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="nama_pelanggan"
                               name="nama_pelanggan"
                               value="{{ old('nama_pelanggan') }}"
                               placeholder="contoh: PT Bahari Segar Abadi / Resto Nelayan"
                               required
                               class="input-field">
                    </div>

                    <div>
                        <label for="no_telp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kontak PIC / No. WhatsApp
                        </label>
                        <input type="text"
                               id="no_telp"
                               name="no_telp"
                               value="{{ old('no_telp') }}"
                               placeholder="contoh: 08123456789"
                               class="input-field">
                    </div>

                    <div>
                        <label for="tgl_pesan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal PO / Pesanan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               id="tgl_pesan"
                               name="tgl_pesan"
                               value="{{ old('tgl_pesan', date('Y-m-d')) }}"
                               required
                               class="input-field">
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Instruksi Khusus / Catatan
                        </label>
                        <input type="text"
                               id="catatan"
                               name="catatan"
                               value="{{ old('catatan') }}"
                               placeholder="contoh: Kemasan styrofoam + es curah tebal"
                               class="input-field">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat_tujuan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Tujuan Pengiriman Cold Storage <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="alamat_tujuan"
                                  name="alamat_tujuan"
                                  rows="2"
                                  required
                                  placeholder="Alamat lengkap penerima / gudang tujuan pengiriman..."
                                  class="input-field">{{ old('alamat_tujuan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Fish Items Breakdown Card -->
            <div class="card p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Daftar Komoditas Ikan yang Dipesan</span>
                    </h3>

                    <button type="button"
                            @click="addItem()"
                            class="btn-secondary text-xs px-3 py-1.5 text-teal-700 hover:text-teal-900 border-teal-200 hover:border-teal-400">
                        + Tambah Komoditas
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, idx) in items" :key="idx">
                        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <!-- Select Fish (5 cols) -->
                            <div class="sm:col-span-5">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                                    Pilih Komoditas <span class="text-rose-500">*</span>
                                </label>
                                <select :name="`items[${idx}][id_ikan]`"
                                        x-model="item.id_ikan"
                                        @change="onFishChange(idx)"
                                        required
                                        class="input-field text-xs">
                                    <option value="">Pilih Ikan...</option>
                                    <template x-for="fish in ikansList" :key="fish.id">
                                        <option :value="fish.id"
                                                x-text="`${fish.nama} (${fish.jenis}) - Stok: ${fish.stok} ${fish.satuan}`">
                                        </option>
                                    </template>
                                </select>
                                <div class="mt-1 flex items-center justify-between text-[10px] text-slate-500">
                                    <span>Harga: <strong class="text-slate-800" x-text="`Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}`"></strong></span>
                                    <span>Tersedia: <strong :class="item.stok <= 100 ? 'text-rose-600' : 'text-emerald-700'" x-text="`${item.stok} ${item.satuan}`"></strong></span>
                                </div>
                            </div>

                            <!-- Quantity (3 cols) -->
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                                    Kuantitas (<span x-text="item.satuan"></span>) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number"
                                       :name="`items[${idx}][jumlah]`"
                                       x-model.number="item.jumlah"
                                       min="0.5"
                                       step="0.5"
                                       required
                                       class="input-field text-xs">
                                <div x-show="item.jumlah > item.stok && item.id_ikan" class="text-[10px] text-rose-600 font-semibold mt-1">
                                    Peringatan: Melebihi stok cold storage!
                                </div>
                            </div>

                            <!-- Subtotal (3 cols) -->
                            <div class="sm:col-span-3">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                                    Subtotal (IDR)
                                </label>
                                <div class="text-sm font-bold text-slate-900 py-2">
                                    Rp <span x-text="new Intl.NumberFormat('id-ID').format(calcSubtotal(item))"></span>
                                </div>
                            </div>

                            <!-- Delete Button (1 col) -->
                            <div class="sm:col-span-1 text-right">
                                <button type="button"
                                        @click="removeItem(idx)"
                                        :disabled="items.length <= 1"
                                        class="p-2 text-slate-400 hover:text-rose-600 disabled:opacity-30 disabled:cursor-not-allowed transition"
                                        title="Hapus Baris">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Order Grand Total Bar -->
                <div class="mt-5 p-4 rounded-xl bg-navy-950 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-teal-400 font-semibold block uppercase tracking-wider">Kalkulasi PO Grosir</span>
                        <div class="text-xs text-slate-300 mt-0.5">
                            Total Kuantitas: <strong class="text-white" x-text="`${totalQty} Kg/Satuan`"></strong>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">Total Nilai Pemesanan</span>
                        <div class="text-xl sm:text-2xl font-black text-white tracking-tight">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalHarga)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('pemesanans.index') }}" class="btn-secondary text-xs">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs px-6 py-2.5">
                    Simpan & Buat Pesanan B2B
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

