<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAsetTetap extends Model
{
    use HasFactory;

    protected $fillable = [
        'akun_aset_id',
        'uraian',
        'tgl_perolehan',
        'harga_beli',
        'golongan',
        'umur_ekonomis',
        'nilai_sisa',
        'akun_beban_id',
        'akun_akumulasi_id',
    ];

    public function akunAset()
    {
        return $this->belongsTo(Akun::class, 'akun_aset_id');
    }

    public function akunBeban()
    {
        return $this->belongsTo(Akun::class, 'akun_beban_id');
    }

    public function akunAkumulasi()
    {
        return $this->belongsTo(Akun::class, 'akun_akumulasi_id');
    }

    public function penyusutans()
    {
        return $this->hasMany(Penyusutan::class, 'detail_aset_id');
    }
}
