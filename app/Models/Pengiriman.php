<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimans';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_pemesanan',
        'nama_kurir_armada',
        'tgl_kirim',
        'tgl_diterima',
        'status_kirim',
        'catatan_pengiriman',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kirim' => 'datetime',
            'tgl_diterima' => 'datetime',
        ];
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}

