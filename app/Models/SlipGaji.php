<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlipGaji extends Model
{
    protected $fillable = [
        'usaha_id', 'karyawan_id', 'nomor_slip', 'periode',
        'gaji_pokok', 'tunjangan_jabatan', 'tunjangan_transport',
        'tunjangan_makan', 'tunjangan_komunikasi', 'tunjangan_lainnya',
        'lembur', 'bonus', 'insentif',
        'pph21', 'bpjs_kesehatan', 'bpjs_jht', 'bpjs_jp',
        'potongan_lainnya', 'keterangan_potongan_lainnya',
        'tempat_ttd', 'tanggal_ttd', 'nama_penandatangan', 'jabatan_penandatangan',
        'status',
    ];

    protected $casts = [
        'periode' => 'date',
        'tanggal_ttd' => 'date',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function getTotalPendapatanAttribute(): float
    {
        return $this->gaji_pokok + $this->tunjangan_jabatan + $this->tunjangan_transport
            + $this->tunjangan_makan + $this->tunjangan_komunikasi + $this->tunjangan_lainnya
            + $this->lembur + $this->bonus + $this->insentif;
    }

    public function getTotalPotonganAttribute(): float
    {
        return $this->pph21 + $this->bpjs_kesehatan + $this->bpjs_jht
            + $this->bpjs_jp + $this->potongan_lainnya;
    }

    public function getTakeHomePayAttribute(): float
    {
        return $this->total_pendapatan - $this->total_potongan;
    }
}