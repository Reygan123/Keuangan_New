<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = ['transaksi_id', 'nomor_nota', 'jenis_nota', 'is_tunai', 'usaha_id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
