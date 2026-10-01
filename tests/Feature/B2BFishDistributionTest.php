<?php

namespace Tests\Feature;

use App\Models\Ikan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Pengiriman;
use App\Models\Stok;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class B2BFishDistributionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_test',
            'email' => 'admin_test@bls.co.id',
            'name' => 'Operational Admin',
            'role' => 'admin',
            'password' => Hash::make('secret123'),
        ]);

        $this->owner = User::create([
            'username' => 'owner_test',
            'email' => 'owner_test@bls.co.id',
            'name' => 'H. Bakri (Owner)',
            'role' => 'owner',
            'password' => Hash::make('secret123'),
        ]);
    }

    public function test_user_can_login_with_username_and_password(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_user_can_login_with_email_and_password(): void
    {
        $this->admin->update(['email' => 'admin_test@bls.co.id']);

        $response = $this->post('/login', [
            'username' => 'admin_test@bls.co.id',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_and_owner_can_access_dashboard(): void
    {
        $responseAdmin = $this->actingAs($this->admin)->get('/dashboard');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Operational Command');

        $responseOwner = $this->actingAs($this->owner)->get('/dashboard');
        $responseOwner->assertStatus(200);
        $responseOwner->assertSee('Executive Oversight');
    }

    public function test_admin_can_create_fish_master_data_with_initial_stock(): void
    {
        $response = $this->actingAs($this->admin)->post('/ikans', [
            'nama_ikan' => 'Ikan Kakap Putih Barramundi',
            'jenis_ikan' => 'Demersal',
            'satuan' => 'Kg',
            'harga_jual' => 95000,
            'initial_stok' => 500,
        ]);

        $response->assertRedirect('/ikans');
        $this->assertDatabaseHas('ikans', [
            'nama_ikan' => 'Ikan Kakap Putih Barramundi',
            'harga_jual' => 95000,
        ]);

        $this->assertDatabaseHas('stoks', [
            'jumlah_stok' => 500,
        ]);
    }

    public function test_admin_can_adjust_cold_storage_stock(): void
    {
        $ikan = Ikan::create([
            'nama_ikan' => 'Ikan Cakalang',
            'jenis_ikan' => 'Pelagis',
            'satuan' => 'Kg',
            'harga_jual' => 40000,
        ]);

        $stok = Stok::create([
            'id_ikan' => $ikan->id_ikan,
            'jumlah_stok' => 100,
            'tanggal_update' => Carbon::now(),
        ]);

        // Restock tambah 150 kg
        $response = $this->actingAs($this->admin)->patch("/stoks/{$stok->id_stok}", [
            'tipe' => 'tambah',
            'jumlah' => 150,
        ]);

        $response->assertRedirect('/stoks');
        $this->assertDatabaseHas('stoks', [
            'id_stok' => $stok->id_stok,
            'jumlah_stok' => 250,
        ]);
    }

    public function test_b2b_wholesale_order_creation_and_automatic_inventory_deduction(): void
    {
        $ikan = Ikan::create([
            'nama_ikan' => 'Tuna Yellowfin Segar',
            'jenis_ikan' => 'Pelagis Besar',
            'satuan' => 'Kg',
            'harga_jual' => 80000,
        ]);

        $stok = Stok::create([
            'id_ikan' => $ikan->id_ikan,
            'jumlah_stok' => 200,
            'tanggal_update' => Carbon::now(),
        ]);

        $orderData = [
            'nama_pelanggan' => 'PT Resto Bahari Utama',
            'no_telp' => '081299998888',
            'alamat_tujuan' => 'Jl. Pelabuhan Ratu No. 10',
            'tgl_pesan' => '2026-10-01',
            'catatan' => 'Suhu -18C wajib terjaga',
            'items' => [
                [
                    'id_ikan' => $ikan->id_ikan,
                    'jumlah' => 50,
                ]
            ]
        ];

        $responseOrder = $this->actingAs($this->admin)->post('/orders', $orderData);
        $responseOrder->assertRedirect('/pemesanans');

        $pemesanan = Pemesanan::where('nama_pelanggan', 'PT Resto Bahari Utama')->first();
        $this->assertNotNull($pemesanan);
        $this->assertEquals('pending', $pemesanan->status_pesan);
        $this->assertEquals(4000000, $pemesanan->total_harga);

        // Admin confirms order -> stock MUST automatically deduct 50 Kg
        $responseConfirm = $this->actingAs($this->admin)->post("/orders/{$pemesanan->id_pemesanan}/confirm");
        $responseConfirm->assertRedirect("/pemesanans/{$pemesanan->id_pemesanan}");

        $stok->refresh();
        $this->assertEquals(150, (float)$stok->jumlah_stok);

        $pemesanan->refresh();
        $this->assertEquals('diproses', $pemesanan->status_pesan);

        $this->assertDatabaseHas('transaksis', [
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'id_user' => $this->admin->id,
        ]);

        $this->assertDatabaseHas('pengirimans', [
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'status_kirim' => 'siap_dikirim',
        ]);
    }

    public function test_internal_fleet_delivery_tracking_and_order_completion(): void
    {
        $ikan = Ikan::create([
            'nama_ikan' => 'Cumi Bangka',
            'jenis_ikan' => 'Cephalopoda',
            'satuan' => 'Kg',
            'harga_jual' => 70000,
        ]);

        $pemesanan = Pemesanan::create([
            'nama_pelanggan' => 'Supermarket Segar',
            'alamat_tujuan' => 'Jakarta Barat',
            'tgl_pesan' => '2026-10-01',
            'total_harga' => 700000,
            'status_pesan' => 'diproses',
        ]);

        $pengiriman = Pengiriman::create([
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'status_kirim' => 'siap_dikirim',
        ]);

        $responseDispatch = $this->actingAs($this->admin)->patch("/pengirimans/{$pengiriman->id_pengiriman}", [
            'nama_kurir_armada' => 'Truk Pendingin BLS-01 / Pak Maman',
            'status_kirim' => 'dalam_perjalanan',
            'catatan_pengiriman' => 'Suhu -18C',
        ]);

        $responseDispatch->assertSessionHas('success');
        $pengiriman->refresh();
        $pemesanan->refresh();

        $this->assertEquals('dalam_perjalanan', $pengiriman->status_kirim);
        $this->assertEquals('dikirim', $pemesanan->status_pesan);

        $responseArrive = $this->actingAs($this->admin)->patch("/pengirimans/{$pengiriman->id_pengiriman}", [
            'nama_kurir_armada' => 'Truk Pendingin BLS-01 / Pak Maman',
            'status_kirim' => 'terkirim',
        ]);

        $pengiriman->refresh();
        $pemesanan->refresh();

        $this->assertEquals('terkirim', $pengiriman->status_kirim);
        $this->assertEquals('selesai', $pemesanan->status_pesan);
    }

    public function test_report_export_to_csv(): void
    {
        $pemesanan = Pemesanan::create([
            'nama_pelanggan' => 'Mitra B2B Export',
            'alamat_tujuan' => 'Tangerang',
            'tgl_pesan' => Carbon::now()->format('Y-m-d'),
            'total_harga' => 5000000,
            'status_pesan' => 'selesai',
        ]);

        Transaksi::create([
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'tanggal_transaksi' => Carbon::now(),
            'list_item' => 'Ikan Cakalang (100 Kg)',
            'id_user' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->owner)->get('/laporan/export/csv');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}

