<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'merchant_id',
        'name',
        'description',
        'price',
        'status',
    ];

    public function merchant(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
