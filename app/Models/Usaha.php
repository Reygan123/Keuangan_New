<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usaha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'alamat', 'email', 'telepon', 'kode_pos',
        'kota', 'provinsi', 'faq', 'website'
    ];
}
