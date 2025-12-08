<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriHppTambahan extends Model
{
    use HasFactory;

    protected $fillable = ['usaha_id', 'name', 'kategori_hpp_id', 'unit_cost', 'akun_biaya_id'];

    public function kategoriHpp()
    {
        return $this->belongsTo(KategoriHpp::class, 'kategori_hpp_id');
    }

    public function akunBiaya()
    {
        return $this->belongsTo(Akun::class, 'akun_biaya_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
