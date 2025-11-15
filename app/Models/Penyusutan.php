<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyusutan extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_aset_id',
        'tanggal_penyusutan',
        'jumlah_penyusutan',
        'akun_beban_id',
        'akun_akumulasi_id',
    ];

    public function detailAset()
    {
        return $this->belongsTo(DetailAsetTetap::class, 'detail_aset_id');
    }

    public function akunBeban()
    {
        return $this->belongsTo(Akun::class, 'akun_beban_id');
    }

    public function akunAkumulasi()
    {
        return $this->belongsTo(Akun::class, 'akun_akumulasi_id');
    }
}
