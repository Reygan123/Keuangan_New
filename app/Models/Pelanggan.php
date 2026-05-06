<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'telepon', 'email', 'keterangan', 'usaha_id'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'pelanggan_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
