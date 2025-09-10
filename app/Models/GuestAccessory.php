<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestAccessory extends Model
{
    use HasFactory;

    protected $table = 'guest_accessories';

    protected $fillable = [
        'guest_id',
        'accessories_heads_id',
        'price',
        'is_returned',
        'total_amount',
        'from_date',
        'to_date'
    ];

    // protected $casts = [
    //     'issued_date' => 'date',
    //     'is_returned' => 'boolean',
    // ];


    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }


    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }


    public function accessoryHead()
    {
        return $this->belongsTo(AccessoryHead::class, 'accessory_head_id');
    }

    public function accessories()
    {
        return $this->belongsToMany(Accessory::class, 'guest_accessories', 'guest_id', 'accessory_head_id')
            ->withPivot(['price', 'total_amount', 'from_date', 'to_date']);
    }


    // public function guest()
    // {
    //     return $this->belongsTo(Guest::class);
    // }

    // public function accessoryHead()
    // {
    //     return $this->belongsTo(AccessoryHead::class, 'accessories_heads_id');
    // }


    // 10092025

    // Accessors
    // public function getStatusAttribute()
    // {
    //     return $this->is_returned ? 'Returned' : 'Issued';
    // }

    // // Scopes
    // public function scopeReturned($query)
    // {
    //     return $query->where('is_returned', true);
    // }

    // public function scopePending($query)
    // {
    //     return $query->where('is_returned', false);
    // }
}
