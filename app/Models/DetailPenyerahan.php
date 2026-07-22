<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPenyerahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_penyerahan_id',
        'nomor_urut',
        'nama_aplikasi',
        'username',
        'email_terkait',
        'password',
        'catatan'
    ];

    public function suratPenyerahan()
    {
        return $this->belongsTo(SuratPenyerahan::class);
    }
}
