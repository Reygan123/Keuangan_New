<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDimuka extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_dimuka';

    protected $fillable = [
        'nama_pembayaran',
        'tgl_transaksi',
        'periode_mulai',
        'periode_akhir',
        'jumlah_nominal',
        'masa_manfaat_bulan',
        'nominal_bulanan',
        'akun_aset_id',
        'akun_kas_id',
        'akun_beban_id',
        'total_diamortisasi',
        'status'
    ];

    public function akunAset()
    {
        return $this->belongsTo(Akun::class, 'akun_aset_id');
    }

    public function akunBeban()
    {
        return $this->belongsTo(Akun::class, 'akun_beban_id');
    }

    public function akunKas()
    {
        return $this->belongsTo(Akun::class, 'akun_kas_id');
    }

    public function amortisasiLog()
    {
        return $this->hasMany(AmortisasiLog::class, 'pembayaran_muka_id');
    }
}
