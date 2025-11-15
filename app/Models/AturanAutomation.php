<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanAutomation extends Model
{
    protected $fillable = [
        'label_id', 'akun_debit_id', 'akun_kredit_id'
    ];

    public function label()
    {
        return $this->belongsTo(LabelTransaksi::class, 'label_id');
    }

    public function akunDebit()
    {
        return $this->belongsTo(Akun::class, 'akun_debit_id');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Akun::class, 'akun_kredit_id');
    }
}
