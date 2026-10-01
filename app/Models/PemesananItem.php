<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananItem extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_items';

    protected $fillable = [
        'id_pemesanan',
        'id_ikan',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'id_ikan', 'id_ikan');
    }
}

