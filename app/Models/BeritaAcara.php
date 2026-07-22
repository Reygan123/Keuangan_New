<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeritaAcara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_surat',
        'judul',
        'hari',
        'tanggal_acara',
        'usaha_id',
        'pihak_pertama_nama',
        'pihak_pertama_jabatan',
        'pihak_pertama_instansi',
        'pihak_kedua_nama',
        'pihak_kedua_jabatan',
        'pihak_kedua_instansi',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function akuns()
    {
        return $this->hasMany(BeritaAcaraAkun::class)->orderBy('nomor_urut');
    }
}
