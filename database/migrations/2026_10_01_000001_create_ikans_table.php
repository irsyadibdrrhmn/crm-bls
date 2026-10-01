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
        Schema::create('ikans', function (Blueprint $table) {
            $table->id('id_ikan');
            $table->string('nama_ikan');
            $table->string('jenis_ikan');
            $table->string('satuan')->default('Kg');
            $table->decimal('harga_jual', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikans');
    }
};

