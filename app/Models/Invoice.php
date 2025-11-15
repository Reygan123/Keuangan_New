<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id', 'nomor_invoice', 'tanggal_jatuh_tempo',
        'jumlah_pajak', 'terms_pembayaran', 'status_invoice'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
