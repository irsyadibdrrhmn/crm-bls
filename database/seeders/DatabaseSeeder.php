<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ikan;
use App\Models\Stok;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Transaksi;
use App\Models\Pengiriman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
    $admin = User::firstOrCreate(
        ['email' => 'admin@gmail.com'], // 💡 Diubah ke email
        [
            'name' => 'Admin Operasional',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]
    );

    $owner = User::firstOrCreate(
        ['email' => 'owner@gmail.com'], // 💡 Diubah ke email
        [
            'name' => 'H. Bakri (Owner)',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]
    );

        // 2. Fish Commodities
        $fishes = [
            ['nama_ikan' => 'Ikan Cakalang Segar', 'jenis_ikan' => 'Pelagis', 'satuan' => 'Kg', 'harga_jual' => 38000, 'stok' => 850],
            ['nama_ikan' => 'Tuna Yellowfin Grade A', 'jenis_ikan' => 'Pelagis Besar', 'satuan' => 'Kg', 'harga_jual' => 85000, 'stok' => 450],
            ['nama_ikan' => 'Tongkol Komo', 'jenis_ikan' => 'Pelagis', 'satuan' => 'Kg', 'harga_jual' => 32000, 'stok' => 1200],
            ['nama_ikan' => 'Kerapu Cantik Hidup', 'jenis_ikan' => 'Demersal', 'satuan' => 'Kg', 'harga_jual' => 115000, 'stok' => 180],
            ['nama_ikan' => 'Kakap Merah Segar', 'jenis_ikan' => 'Demersal', 'satuan' => 'Kg', 'harga_jual' => 72000, 'stok' => 320],
            ['nama_ikan' => 'Cumi-Cumi Bangka Tube', 'jenis_ikan' => 'Cephalopoda', 'satuan' => 'Kg', 'harga_jual' => 88000, 'stok' => 500],
            ['nama_ikan' => 'Udang Vaname Size 40', 'jenis_ikan' => 'Crustacea', 'satuan' => 'Kg', 'harga_jual' => 78000, 'stok' => 650],
            ['nama_ikan' => 'Tenggiri Bulat Utuh', 'jenis_ikan' => 'Pelagis', 'satuan' => 'Kg', 'harga_jual' => 95000, 'stok' => 260],
            ['nama_ikan' => 'Bawal Hitam', 'jenis_ikan' => 'Pelagis', 'satuan' => 'Kg', 'harga_jual' => 65000, 'stok' => 340],
            ['nama_ikan' => 'Salmon Fillet Premium (Import)', 'jenis_ikan' => 'Salmonidae', 'satuan' => 'Kg', 'harga_jual' => 240000, 'stok' => 95],
        ];

        $ikanModels = [];
        foreach ($fishes as $item) {
            $ikan = Ikan::create([
                'nama_ikan' => $item['nama_ikan'],
                'jenis_ikan' => $item['jenis_ikan'],
                'satuan' => $item['satuan'],
                'harga_jual' => $item['harga_jual'],
            ]);

            Stok::create([
                'id_ikan' => $ikan->id_ikan,
                'jumlah_stok' => $item['stok'],
                'tanggal_update' => now(),
            ]);

            $ikanModels[] = $ikan;
        }

        // 3. Sample B2B Wholesale Orders
        $order1 = Pemesanan::create([
            'id_user' => $admin->id,
            'nama_pelanggan' => 'PT Mina Sejahtera Nusantara',
            'nik' => '3172010203850001',
            'no_telp' => '081298765432',
            'alamat_tujuan' => 'Kawasan Industri Perikanan Muara Baru Kav. 12, Jakarta Utara',
            'tgl_pesan' => now()->subDays(5)->format('Y-m-d'),
            'total_harga' => 12750000,
            'metode_pembayaran' => 'transfer_bank',
            'nama_bank' => 'BCA',
            'status_pesan' => 'selesai',
            'catatan' => 'Kemasan boks styrofoam berinsulasi es batu curah. Siap kirim subuh.',
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order1->id_pemesanan,
            'id_ikan' => $ikanModels[1]->id_ikan, // Tuna Yellowfin
            'jumlah' => 150,
            'harga_satuan' => 85000,
            'subtotal' => 12750000,
        ]);

        Transaksi::create([
            'id_pemesanan' => $order1->id_pemesanan,
            'tanggal_transaksi' => now()->subDays(5)->setTime(8, 30),
            'list_item' => 'Tuna Yellowfin Grade A (150 Kg @ Rp 85.000)',
            'metode_pembayaran' => 'transfer_bank',
            'nama_bank' => 'BCA',
            'id_user' => $admin->id,
        ]);

        Pengiriman::create([
            'id_pemesanan' => $order1->id_pemesanan,
            'nama_kurir_armada' => 'Truck Cold Storage 01 (B 9102 BLS) - Bpk. Bambang',
            'tgl_kirim' => now()->subDays(5)->setTime(5, 0),
            'tgl_diterima' => now()->subDays(5)->setTime(9, 30),
            'status_kirim' => 'terkirim',
            'catatan_pengiriman' => 'Diterima dalam kondisi suhu boks -4 derajat Celcius oleh Staff QC Gudang.',
        ]);

        // Order 2: Dikirim
        $order2 = Pemesanan::create([
            'id_user' => $admin->id,
            'nama_pelanggan' => 'Restoran Seafood Teluk Rasa Mandiri',
            'nik' => '3172021508820003',
            'no_telp' => '082133445566',
            'alamat_tujuan' => 'Jl. Boulevard Kelapa Gading No. 88, Jakarta Utara',
            'tgl_pesan' => now()->subDays(1)->format('Y-m-d'),
            'total_harga' => 9740000,
            'metode_pembayaran' => 'cash',
            'nama_bank' => null,
            'status_pesan' => 'dikirim',
            'catatan' => 'Kerapu wajib hidup dalam drum aerasi beroksigen aktif.',
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order2->id_pemesanan,
            'id_ikan' => $ikanModels[3]->id_ikan, // Kerapu Cantik
            'jumlah' => 40,
            'harga_satuan' => 115000,
            'subtotal' => 4600000,
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order2->id_pemesanan,
            'id_ikan' => $ikanModels[5]->id_ikan, // Cumi Tube
            'jumlah' => 40,
            'harga_satuan' => 88000,
            'subtotal' => 3520000,
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order2->id_pemesanan,
            'id_ikan' => $ikanModels[6]->id_ikan, // Udang Vaname
            'jumlah' => 20,
            'harga_satuan' => 81000,
            'subtotal' => 1620000,
        ]);

        Transaksi::create([
            'id_pemesanan' => $order2->id_pemesanan,
            'tanggal_transaksi' => now()->subDays(1)->setTime(14, 0),
            'list_item' => 'Kerapu Cantik Hidup (40 Kg), Cumi-Cumi Bangka Tube (40 Kg), Udang Vaname Size 40 (20 Kg)',
            'metode_pembayaran' => 'cash',
            'nama_bank' => null,
            'id_user' => $admin->id,
        ]);

        Pengiriman::create([
            'id_pemesanan' => $order2->id_pemesanan,
            'nama_kurir_armada' => 'Pick-Up Aerasi Laut 02 (B 8721 BLS) - Bpk. Subur',
            'tgl_kirim' => now()->subHours(2),
            'tgl_diterima' => null,
            'status_kirim' => 'dalam_perjalanan',
            'catatan_pengiriman' => 'Kurir sedang dalam rute tol Cakung arah Kelapa Gading.',
        ]);

        // Order 3: Diproses
        $order3 = Pemesanan::create([
            'id_user' => $admin->id,
            'nama_pelanggan' => 'Hotel Grand Bahari Resort & Spa',
            'nik' => '3172052006790002',
            'no_telp' => '081399887711',
            'alamat_tujuan' => 'Jl. Pantai Indah Kapuk No. 1, Jakarta Utara',
            'tgl_pesan' => now()->format('Y-m-d'),
            'total_harga' => 12000000,
            'metode_pembayaran' => 'transfer_bank',
            'nama_bank' => 'Mandiri',
            'status_pesan' => 'diproses',
            'catatan' => 'Salmon fillet vacuum packed portion 200gr.',
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order3->id_pemesanan,
            'id_ikan' => $ikanModels[9]->id_ikan, // Salmon
            'jumlah' => 50,
            'harga_satuan' => 240000,
            'subtotal' => 12000000,
        ]);

        Transaksi::create([
            'id_pemesanan' => $order3->id_pemesanan,
            'tanggal_transaksi' => now()->setTime(10, 15),
            'list_item' => 'Salmon Fillet Premium (Import) (50 Kg @ Rp 240.000)',
            'metode_pembayaran' => 'transfer_bank',
            'nama_bank' => 'Mandiri',
            'id_user' => $admin->id,
        ]);

        Pengiriman::create([
            'id_pemesanan' => $order3->id_pemesanan,
            'nama_kurir_armada' => 'Truck Cold Storage 02 (B 9445 BLS) - Bpk. Joko',
            'tgl_kirim' => null,
            'tgl_diterima' => null,
            'status_kirim' => 'siap_dikirim',
            'catatan_pengiriman' => 'Menunggu proses loading muatan ke cold unit.',
        ]);

        // Order 4: Pending
        $order4 = Pemesanan::create([
            'id_user' => $admin->id,
            'nama_pelanggan' => 'Catering Berkah Nelayan Abadi',
            'nik' => '3172041112900004',
            'no_telp' => '087766554433',
            'alamat_tujuan' => 'Jl. Danau Sunter Barat Blok A-3, Jakarta Utara',
            'tgl_pesan' => now()->format('Y-m-d'),
            'total_harga' => 6400000,
            'metode_pembayaran' => 'cash',
            'nama_bank' => null,
            'status_pesan' => 'pending',
            'catatan' => 'Order baru masuk, menunggu konfirmasi stok fisik cold room.',
        ]);

        PemesananItem::create([
            'id_pemesanan' => $order4->id_pemesanan,
            'id_ikan' => $ikanModels[2]->id_ikan, // Tongkol Komo
            'jumlah' => 200,
            'harga_satuan' => 32000,
            'subtotal' => 6400000,
        ]);

        // 4. Historical Monthly Transactions for realistic 6-month Trend Analytics
        $historicalClients = [
            ['name' => 'PT Samudera Bahari Sentosa', 'nik' => '3172011204850001', 'telp' => '081122334455', 'addr' => 'Kawasan Industri Pelabuhan Tanjung Priok Blok C-2, Jakarta'],
            ['name' => 'Restoran Aroma Laut Senayan', 'nik' => '3171022409830002', 'telp' => '081234567890', 'addr' => 'Komplek Gelora Bung Karno Pintu 7, Jakarta Pusat'],
            ['name' => 'CV Mina Makmur Sejahtera', 'nik' => '3172031506880003', 'telp' => '081345678901', 'addr' => 'Jl. Lodan Timur No. 15, Ancol, Jakarta Utara'],
            ['name' => 'Supermarket Bahari Fresh BSD', 'nik' => '3674011802910004', 'telp' => '081456789012', 'addr' => 'BSD Grand Boulevard Kav. 8, Tangerang Selatan'],
            ['name' => 'Hotel Grand Mercure Kemayoran', 'nik' => '3171052911800005', 'telp' => '081567890123', 'addr' => 'Jl. H. Benyamin Sueb Kav. B-6, Kemayoran, Jakarta Pusat'],
            ['name' => 'Catering Selera Nusantara Prima', 'nik' => '3172040807860006', 'telp' => '081678901234', 'addr' => 'Jl. Danau Sunter Selatan Blok O No. 12, Jakarta Utara'],
            ['name' => 'Pusat Kuliner Ikan Bakar Muara Karang', 'nik' => '3172061210840007', 'telp' => '081789012345', 'addr' => 'Jl. Pluit Karang Cantik No. 44, Muara Karang, Jakarta Utara'],
            ['name' => 'PT Pasifik Boga Lestari', 'nik' => '3172010101820008', 'telp' => '081890123456', 'addr' => 'Kawasan Pergudangan Pluit Blok H No. 5, Jakarta Utara'],
        ];

        $pastMonthsData = [
            5 => [ // 5 months ago
                ['client' => 0, 'day' => 12, 'items' => [[0, 300], [2, 400]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 1, 'day' => 22, 'items' => [[1, 200], [4, 150]], 'courier' => 'Pick-Up Aerasi Laut 02 - Bpk. Subur'],
            ],
            4 => [ // 4 months ago
                ['client' => 2, 'day' => 8, 'items' => [[2, 500], [7, 150]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
                ['client' => 3, 'day' => 16, 'items' => [[5, 200], [6, 250]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 4, 'day' => 25, 'items' => [[9, 100], [1, 150]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
            ],
            3 => [ // 3 months ago
                ['client' => 5, 'day' => 5, 'items' => [[0, 450], [4, 200]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 6, 'day' => 14, 'items' => [[3, 100], [6, 200]], 'courier' => 'Pick-Up Aerasi Laut 02 - Bpk. Subur'],
                ['client' => 0, 'day' => 21, 'items' => [[1, 250], [7, 180]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
                ['client' => 1, 'day' => 28, 'items' => [[5, 250], [8, 200]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
            ],
            2 => [ // 2 months ago
                ['client' => 2, 'day' => 3, 'items' => [[0, 500], [2, 600]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 3, 'day' => 11, 'items' => [[9, 120], [4, 250]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
                ['client' => 4, 'day' => 18, 'items' => [[1, 300], [3, 120]], 'courier' => 'Pick-Up Aerasi Laut 02 - Bpk. Subur'],
                ['client' => 7, 'day' => 24, 'items' => [[6, 350], [5, 220]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 6, 'day' => 29, 'items' => [[7, 200], [8, 250]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
            ],
            1 => [ // 1 month ago
                ['client' => 0, 'day' => 4, 'items' => [[1, 350], [9, 150]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
                ['client' => 1, 'day' => 9, 'items' => [[0, 600], [4, 300]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 5, 'day' => 15, 'items' => [[2, 700], [6, 400]], 'courier' => 'Pick-Up Aerasi Laut 02 - Bpk. Subur'],
                ['client' => 2, 'day' => 20, 'items' => [[3, 150], [5, 300]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
                ['client' => 3, 'day' => 25, 'items' => [[7, 250], [8, 300]], 'courier' => 'Truck Cold Storage 02 - Bpk. Joko'],
                ['client' => 7, 'day' => 28, 'items' => [[1, 200], [4, 200]], 'courier' => 'Truck Cold Storage 01 - Bpk. Bambang'],
            ],
        ];

        $orderCounter = 0;
        foreach ($pastMonthsData as $monthsAgo => $ordersList) {
            foreach ($ordersList as $ordData) {
                $orderCounter++;
                $client = $historicalClients[$ordData['client']];
                $orderDate = now()->subMonths($monthsAgo)->setDay($ordData['day'])->setTime(9, 0);

                $totalHarga = 0;
                $itemsToCreate = [];
                $descItems = [];

                foreach ($ordData['items'] as $itemPair) {
                    $ikanIdx = $itemPair[0];
                    $qty = $itemPair[1];
                    $ikanObj = $ikanModels[$ikanIdx];
                    $subtotal = $qty * $ikanObj->harga_jual;
                    $totalHarga += $subtotal;

                    $itemsToCreate[] = [
                        'id_ikan' => $ikanObj->id_ikan,
                        'jumlah' => $qty,
                        'harga_satuan' => $ikanObj->harga_jual,
                        'subtotal' => $subtotal,
                    ];
                    $descItems[] = "{$ikanObj->nama_ikan} ({$qty} Kg)";
                }

                $metodeBayar = ($orderCounter % 3 === 0) ? 'cash' : 'transfer_bank';
                $namaBank = $metodeBayar === 'transfer_bank' ? ($orderCounter % 2 === 0 ? 'BCA' : 'Mandiri') : null;

                $pastOrder = Pemesanan::create([
                    'id_user' => $admin->id,
                    'nama_pelanggan' => $client['name'],
                    'nik' => $client['nik'],
                    'no_telp' => $client['telp'],
                    'alamat_tujuan' => $client['addr'],
                    'tgl_pesan' => $orderDate->format('Y-m-d'),
                    'total_harga' => $totalHarga,
                    'metode_pembayaran' => $metodeBayar,
                    'nama_bank' => $namaBank,
                    'status_pesan' => 'selesai',
                    'catatan' => 'Pesanan rutin B2B telah diproses dan terkirim dengan standar cold chain.',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                foreach ($itemsToCreate as $it) {
                    PemesananItem::create([
                        'id_pemesanan' => $pastOrder->id_pemesanan,
                        'id_ikan' => $it['id_ikan'],
                        'jumlah' => $it['jumlah'],
                        'harga_satuan' => $it['harga_satuan'],
                        'subtotal' => $it['subtotal'],
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                Transaksi::create([
                    'id_pemesanan' => $pastOrder->id_pemesanan,
                    'tanggal_transaksi' => $orderDate->copy()->addHours(1),
                    'list_item' => implode(', ', $descItems),
                    'metode_pembayaran' => $metodeBayar,
                    'nama_bank' => $namaBank,
                    'id_user' => $admin->id,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                Pengiriman::create([
                    'id_pemesanan' => $pastOrder->id_pemesanan,
                    'nama_kurir_armada' => $ordData['courier'],
                    'tgl_kirim' => $orderDate->copy()->addHours(2),
                    'tgl_diterima' => $orderDate->copy()->addHours(6),
                    'status_kirim' => 'terkirim',
                    'catatan_pengiriman' => 'Pengiriman selesai, suhu cold storage terjaga stabil.',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }
        }
    }
}
