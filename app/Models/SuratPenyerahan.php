<?php
// app/Models/SuratPenyerahan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratPenyerahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_surat',
        'tipe_surat',
        'perihal',
        'lampiran',
        'tanggal_surat',
        'tempat_surat',
        'pihak_pertama_nama',
        'pihak_pertama_jabatan',
        'pihak_pertama_instansi',
        'pihak_pertama_nip',
        'pihak_kedua_nama',
        'pihak_kedua_jabatan',
        'pihak_kedua_instansi',
        'pihak_kedua_nip',
        'deskripsi_penyerahan',
        'keterangan',
        'status',
        'usaha_id'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function detailPenyerahans()
    {
        return $this->hasMany(DetailPenyerahan::class)->orderBy('nomor_urut');
    }
}
