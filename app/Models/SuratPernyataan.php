<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPernyataan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'usaha_id',
        'nama_lengkap',
        'jabatan',
        'departemen',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'tanggal_surat',
        'tempat_ttd',
        'nama_pejabat',
        'jabatan_pejabat',
        'status',
        'catatan',
        'description'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
