<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->foreignId('id_user')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->string('nama_pelanggan');
            $table->string('no_telp')->nullable();
            $table->text('alamat_tujuan')->nullable();
            $table->date('tgl_pesan');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status_pesan', ['pending', 'diproses', 'dikirim', 'selesai'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};

