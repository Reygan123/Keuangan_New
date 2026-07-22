<?php
// app/Models/JenisSurat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_surat',
        'initial_code',
        'nama_jenis',
        'keterangan'
    ];

    protected $casts = [
        'kode_surat' => 'string',
        'initial_code' => 'string',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
