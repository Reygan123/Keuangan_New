<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransaksiPajak extends Model
{
    protected $fillable = [
        'usaha_id', 'jenis_pajak_id', 'referensi_id', 'referensi_tipe',
        'dasar_pengenaan_pajak', 'tarif_persen', 'jumlah_pajak',
        'jurnal_umum_id', 'status', 'tanggal_setor',
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function jenisPajak(): BelongsTo
    {
        return $this->belongsTo(JenisPajak::class);
    }

    public function jurnalUmum(): BelongsTo
    {
        return $this->belongsTo(JurnalUmum::class);
    }

    public function referensi(): MorphTo
    {
        return $this->morphTo(null, 'referensi_tipe', 'referensi_id');
    }
}