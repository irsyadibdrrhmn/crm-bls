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
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('nama_pelanggan');
            $table->enum('metode_pembayaran', ['cash', 'transfer_bank'])->default('transfer_bank')->after('total_harga');
            $table->string('nama_bank', 50)->nullable()->after('metode_pembayaran');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['cash', 'transfer_bank'])->default('transfer_bank')->after('list_item');
            $table->string('nama_bank', 50)->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn(['nik', 'metode_pembayaran', 'nama_bank']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'nama_bank']);
        });
    }
};

