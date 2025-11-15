<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriHpp extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'kategori'];

    public function tambahans()
    {
        return $this->hasMany(KategoriHppTambahan::class, 'kategori_hpp_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_hpp_id');
    }
}
