<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeritaAcaraAkun extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_acara_id',
        'nomor_urut',
        'nama_aplikasi',
        'username',
        'email_terkait',
        'password'
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class);
    }
}
