<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{
    use HasFactory;

    protected $table = 'ikans';
    protected $primaryKey = 'id_ikan';

    protected $fillable = [
        'nama_ikan',
        'jenis_ikan',
        'satuan',
        'harga_jual',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual' => 'decimal:2',
        ];
    }

    public function stok()
    {
        return $this->hasOne(Stok::class, 'id_ikan', 'id_ikan');
    }

    public function pemesananItems()
    {
        return $this->hasMany(PemesananItem::class, 'id_ikan', 'id_ikan');
    }
}

