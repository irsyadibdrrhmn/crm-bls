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
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_pemesanan')->constrained('pemesanans', 'id_pemesanan')->cascadeOnDelete();
            $table->string('nama_kurir_armada')->nullable()->default('Belum Ditugaskan');
            $table->dateTime('tgl_kirim')->nullable();
            $table->dateTime('tgl_diterima')->nullable();
            $table->enum('status_kirim', ['siap_dikirim', 'dalam_perjalanan', 'terkirim'])->default('siap_dikirim');
            $table->text('catatan_pengiriman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};

