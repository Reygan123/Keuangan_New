<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmortisasiLog extends Model
{
    use HasFactory;

    protected $table = 'amortisasi_log';

    protected $fillable = [
        'pembayaran_muka_id',
        'tanggal_amortisasi',
        'jumlah_amortisasi',
        'jurnal_umum_id'
    ];

    public function pembayaranDimuka()
    {
        return $this->belongsTo(PembayaranDimuka::class, 'pembayaran_muka_id');
    }

    public function jurnalUmum()
    {
        return $this->belongsTo(JurnalUmum::class, 'jurnal_umum_id');
    }
}
