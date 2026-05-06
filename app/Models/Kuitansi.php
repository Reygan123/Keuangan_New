<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuitansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id', 'nomor_kuitansi', 'tanggal_pembayaran',
        'metode_pembayaran', 'jumlah_dibayar', 'tanda_tangan_penerima', 'usaha_id'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
