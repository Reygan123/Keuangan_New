<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiRekening extends Model
{
    use HasFactory;

    protected $table = 'mutasi_rekening';

    protected $fillable = [
        'tanggal',
        'akun_asal_id',
        'akun_tujuan_id',
        'jumlah',
        'deskripsi',
        'jurnal_referensi_id',
        'usaha_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function akunAsal()
    {
        return $this->belongsTo(Akun::class, 'akun_asal_id');
    }

    public function akunTujuan()
    {
        return $this->belongsTo(Akun::class, 'akun_tujuan_id');
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'referensi_transaksi_id')
                    ->where('referensi_transaksi_tipe', get_class($this));
    }
}
