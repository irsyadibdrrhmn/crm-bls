<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_user',
        'nama_pelanggan',
        'no_telp',
        'alamat_tujuan',
        'tgl_pesan',
        'total_harga',
        'status_pesan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pesan' => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function items()
    {
        return $this->hasMany(PemesananItem::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pemesanan', 'id_pemesanan');
    }
}

