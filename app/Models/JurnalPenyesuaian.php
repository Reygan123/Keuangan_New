<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class JurnalPenyesuaian extends Model
{
    protected $fillable = [
        'usaha_id',
        'tanggal_transaksi',
        'no_bukti',
        'keterangan',
        'created_by',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Menghubungkan ke baris-baris di jurnal_umum via kolom polymorphic yang sudah ada
    public function baris(): MorphMany
    {
        return $this->morphMany(JurnalUmum::class, 'referensi_transaksi', 'referensi_transaksi_tipe', 'referensi_transaksi_id');
    }

    public function getTotalDebitAttribute()
    {
        return $this->baris->sum('debit');
    }

    public function getTotalKreditAttribute()
    {
        return $this->baris->sum('kredit');
    }
}