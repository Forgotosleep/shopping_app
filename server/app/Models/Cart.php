<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_id',
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Transaction::class, 'trx_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
