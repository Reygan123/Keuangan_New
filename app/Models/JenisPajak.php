<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPajak extends Model
{
    protected $fillable = [
        'usaha_id', 'kode', 'nama', 'kategori', 'arah',
        'tarif_persen', 'akun_id', 'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'tarif_persen' => 'decimal:2',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }

    public function aturanPajaks(): HasMany
    {
        return $this->hasMany(AturanPajak::class);
    }

    public function isWithholding(): bool
    {
        return $this->kategori === 'WITHHOLDING';
    }

    public function isPpn(): bool
    {
        return $this->kategori === 'PPN';
    }

    public function isPpnKeluaran(): bool
    {
        return $this->isPpn() && $this->arah === 'KELUARAN';
    }

    public function isPpnMasukan(): bool
    {
        return $this->isPpn() && $this->arah === 'MASUKAN';
    }
}