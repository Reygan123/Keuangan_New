<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'label_id', 'pelanggan_id', 'supplier_id', 'akun_payment_id', 'akun_lawan_id', 'tanggal', 'jumlah', 'keterangan', 'usaha_id'
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function label()
    {
        return $this->belongsTo(LabelTransaksi::class, 'label_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function akunPayment()
    {
        return $this->belongsTo(Akun::class, 'akun_payment_id');
    }

    public function akunLawan()
    {
        return $this->belongsTo(Akun::class, 'akun_lawan_id');
    }

    public function detailProduks()
    {
        return $this->hasMany(TransaksiDetailProduk::class, 'transaksi_id');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'id');
    }
    public function receipt()
    {
        return $this->hasMany(Receipt::class, 'id');
    }
    public function kuitansi()
    {
        return $this->hasMany(Kuitansi::class, 'id');
    }
    public function nota()
    {
        return $this->hasMany(Nota::class, 'id');
    }
}
