<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaMagang extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_pemberitahuan_id',
        'nomor_urut',
        'nama_lengkap',
        'asal_perguruan_tinggi',
        'posisi'
    ];

    public function suratPemberitahuan()
    {
        return $this->belongsTo(SuratPemberitahuan::class);
    }
}
