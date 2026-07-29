<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $fillable = [
        'usaha_id', 'nama', 'posisi', 'divisi', 'no_rekening',
        'nik', 'npwp', 'status_kepegawaian', 'tanggal_bergabung',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function slipGajis(): HasMany
    {
        return $this->hasMany(SlipGaji::class);
    }
}