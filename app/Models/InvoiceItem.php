<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id', 'description', 'qty', 'harga', 'total'
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Auto calculate total sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total = $model->qty * $model->harga;
        });
    }
}
