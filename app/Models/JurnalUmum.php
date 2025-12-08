<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $fillable = [
        'akun_id',
        'tanggal_transaksi',
        'deskripsi',
        'debit',
        'kredit',
        'referensi_transaksi_id',
        'referensi_transaksi_tipe',
        'sumber_log_type',
        'sumber_log_id',
        'usaha_id'
    ];

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }

    public function referensiTransaksi(): MorphTo
    {
        return $this->morphTo(null, 'referensi_transaksi_tipe', 'referensi_transaksi_id');
    }

    public function sumberPenyusutan()
    {
        return $this->belongsTo(Penyusutan::class, 'sumber_log_id')->where('sumber_log_type', 'penyusutan');
    }

    public function sumberAmortisasi()
    {
        return $this->belongsTo(AmortisasiLog::class, 'sumber_log_id')->where('sumber_log_type', 'amortisasi');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
