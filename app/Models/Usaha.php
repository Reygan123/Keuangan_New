<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Usaha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'alamat', 'email', 'telepon', 'kode_pos',
        'kota', 'provinsi', 'faq', 'website'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'usaha_user')->withPivot('role');
    }

    public function akuns()
    {
        return $this->hasMany(Akun::class);
    }
}
