<?php
// app/Models/Surat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'nomor_urut',
        'jenis_surat_id',
        'kode_unit',
        'kode_perusahaan',
        'bulan',
        'tahun',
        'keterangan',
        'tanggal_dikeluarkan',
        'usaha_id'
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'tanggal_dikeluarkan' => 'date',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
