<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriHppTambahan extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_hpp_id', 'name','unit_cost','akun_biaya_id'];

    public function kategoriHpp()
    {
        return $this->belongsTo(KategoriHpp::class, 'kategori_hpp_id');
    }

    public function akunBiaya()
    {
        return $this->belongsTo(Akun::class, 'akun_biaya_id');
    }
}
