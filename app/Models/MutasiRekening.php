<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiRekening extends Model
{
    use HasFactory;

    protected $table = 'mutasi_rekening';

    protected $fillable = [
        'tanggal',
        'akun_asal_id', // Foreign key ke Akun
        'akun_tujuan_id', // Foreign key ke Akun
        'jumlah',
        'deskripsi',
        'jurnal_referensi_id', // Digunakan untuk mengelompokkan entri Jurnal Umum
    ];

    // Casting untuk memastikan tanggal selalu berupa objek tanggal
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi: Mutasi berasal dari satu Akun (Akun yang saldonya berkurang)
     */
    public function akunAsal()
    {
        return $this->belongsTo(Akun::class, 'akun_asal_id');
    }

    /**
     * Relasi: Mutasi masuk ke satu Akun (Akun yang saldonya bertambah)
     */
    public function akunTujuan()
    {
        return $this->belongsTo(Akun::class, 'akun_tujuan_id');
    }

    /**
     * Relasi: Mendapatkan semua entri Jurnal Umum yang dibuat oleh Mutasi ini
     * (Ada dua entri jurnal untuk setiap satu Mutasi)
     */
    public function jurnalUmum()
    {
        // Mencari semua jurnal yang memiliki referensi ke ID mutasi ini
        // dan referensi ID grup jurnal yang sama.
        return $this->hasMany(JurnalUmum::class, 'referensi_transaksi_id')
                    ->where('referensi_transaksi_tipe', get_class($this));
    }
}
