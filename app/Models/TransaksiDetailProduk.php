<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetailProduk extends Model
{
    protected $fillable = [
        'transaksi_id', 'product_id', 'kuantitas', 'harga_satuan'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
