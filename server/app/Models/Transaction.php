<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'merchant_id',
        'unique_id',
        'total_price',
        'status',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function merchant(): \Illuminate\Database\Eloquent\Relations\belongsTo
	{
		return $this->belongsTo(Merchant::class, 'id_merchant');
	}
}
