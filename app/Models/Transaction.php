<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'bank_name',
        'payment_mode',
        'txn_amount',
        'currency',
        'response_code',
        'response_message',
        'bank_txn_id',
        'm_id',
        'response_payload',
    ];

    // protected $casts = [
    //     'response_data' => 'array',
    // ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }


     public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
