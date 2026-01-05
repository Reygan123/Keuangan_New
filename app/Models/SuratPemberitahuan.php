<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratPemberitahuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_surat',
        'judul_indonesia',
        'judul_inggris',
        'kepada',
        'isi_surat',
        'penutup',
        'usaha_id',
        'status',
        'tanggal_surat',
        'tempat_surat',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'nip_penandatangan'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function pesertaMagangs()
    {
        return $this->hasMany(PesertaMagang::class)->orderBy('nomor_urut');
    }
}
