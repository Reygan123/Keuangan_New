<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $fillable = [
        'kode',
        'name',
        'saldo',
        'klasifikasi',
        'aktivitas_kas',
        'nama_kelompok',
        'sub_klasifikasi',
        'usaha_id'
    ];

    // 27-3-2026
    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }

    public function mutasiAsal()
    {
        return $this->hasMany(MutasiRekening::class, 'akun_asal_id');
    }

    public function mutasiTujuan()
    {
        return $this->hasMany(MutasiRekening::class, 'akun_tujuan_id');
    }

    public function aturanAutomationsDebit()
    {
        return $this->hasMany(AturanAutomation::class, 'akun_debit_id');
    }

    public function aturanAutomationsKredit()
    {
        return $this->hasMany(AturanAutomation::class, 'akun_kredit_id');
    }

    public function biayaTambahan()
    {
        return $this->hasMany(KategoriHppTambahan::class, 'akun_biaya_id');
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'akun_id');
    }
}
