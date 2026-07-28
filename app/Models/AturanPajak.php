<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AturanPajak extends Model
{
    protected $fillable = [
        'usaha_id', 'label_id', 'product_id', 'jenis_pajak_id', 'tarif_override',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(LabelTransaksi::class, 'label_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function jenisPajak(): BelongsTo
    {
        return $this->belongsTo(JenisPajak::class);
    }

    public function getTarifEfektifAttribute(): float
    {
        return $this->tarif_override ?? $this->jenisPajak->tarif_persen;
    }
}