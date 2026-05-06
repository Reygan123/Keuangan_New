<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelTransaksi extends Model
{
    protected $fillable = [
        'nama_label', 'deskripsi', 'tipe_utama', 'usaha_id'
    ];

    public function aturanAutomations()
    {
        return $this->hasMany(AturanAutomation::class, 'label_id');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'label_id');
    }

    public function usaha()
    {
        return $this->belongsTo(Usaha::class);
    }
}
