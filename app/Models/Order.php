<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'user_id',
        'guest_id',
        'order_id',
        'amount',
        'payment_id',
        'status',
        'message',
        'payment_method',
        'purpose',
        'origin_url',
        'redirect_url',
        'callback_route',
        'metadata',
    ];

    // protected $casts = [
    //     'response_data' => 'array',
    // ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($order) {
    //         // $lastSeq = Order::max('sequence') ?? 0;
    //         $count = Order::count();
    //         $newSeq = $count + 1;
    //         $order->order_id = self::generateOrderId($newSeq);
    //     });
    // }

    // public static function generateOrderId($seq)
    // {
    //     $seqStr = strval($seq);

    //     // Pad to minimum 4 digits
    //     if (strlen($seqStr) < 4) {
    //         $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
    //     }

    //     // Replace each '0' with a random uppercase letter
    //     $seqStr = preg_replace_callback('/0/', function () {
    //         return chr(rand(65, 90)); // A–Z
    //     }, $seqStr);

    //     $prefix = 'ORD';
    //     $date = now()->format('ymd'); // e.g. 250826

    //     return "{$prefix}-{$date}-{$seqStr}";
    // }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id');
    }

    // Relation with Transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }



    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'order_id');
    }




    // 🔹 Automatically generate IDs on model creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generate UUID for reference_id
            $order->reference_id = Str::uuid()->toString();

            // Generate custom order_id
            $order->order_id = self::generateOrderId();
        });
    }

    // 🔹 Custom order ID generator
    public static function generateOrderId()
    {
        $count = self::count();
        $seq = $count + 1;
        $seqStr = strval($seq);

        // Pad to minimum 4 digits
        if (strlen($seqStr) < 4) {
            $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
        }

        // Replace each '0' with a random uppercase letter
        $seqStr = preg_replace_callback('/0/', function () {
            return chr(rand(65, 90)); // A–Z
        }, $seqStr);

        $prefix = 'G-ORD';
        $date = now()->format('ymd'); // e.g. 250826

        return "{$prefix}{$date}{$seqStr}";
    }

}
