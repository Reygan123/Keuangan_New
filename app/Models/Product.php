<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori_hpp_id',
        'hpp_unit_rata2',
        'akun_pendapatan_id',
        'akun_persediaan_id',
        'akun_hpp_id',
        'satuan_unit',
        'stok',
        'usaha_id'
    ];

    public function kategoriHpp()
    {
        return $this->belongsTo(KategoriHpp::class, 'kategori_hpp_id');
    }

    public function akunPendapatan()
    {
        return $this->belongsTo(Akun::class, 'akun_pendapatan_id');
    }

    public function akunPersediaan()
    {
        return $this->belongsTo(Akun::class, 'akun_persediaan_id');
    }

    public function akunHpp()
    {
        return $this->belongsTo(Akun::class, 'akun_hpp_id');
    }

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetailProduk::class, 'product_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
