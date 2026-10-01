<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stoks';
    protected $primaryKey = 'id_stok';

    protected $fillable = [
        'id_ikan',
        'jumlah_stok',
        'tanggal_update',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_stok' => 'decimal:2',
            'tanggal_update' => 'datetime',
        ];
    }

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'id_ikan', 'id_ikan');
    }
}

