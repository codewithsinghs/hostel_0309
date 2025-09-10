<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $table = 'accessories';

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    protected $fillable = [
        'name',
        'accessories_heads_id',
        'price',
        'is_default',
        'from_date',
        'to_date',
        'is_active',
        'created_by',
    ];

    // Relationship: Accessory belongs to AccessoryHead
    // public function accessoryHead()
    // {
    //     return $this->belongsTo(AccessoryHead::class);
    // }

    // public function accessoryHead()
    // {
    //     return $this->belongsTo(AccessoryHead::class, 'accessories_heads_id');
    // }

    // (Optional) created_by user relationship if needed
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function accessory()
    // {
    //     return $this->hasMany(GuestAccessory::class);
    // } 

    // (Optional) created_by user relationship if needed
    public function resident()
    {
        return $this->belongsTo(User::class);
    }

    // 10092025

    public function accessoryHead()
    {
        return $this->belongsTo(AccessoryHead::class);
    }

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'guest_accessories')
                    ->withPivot('issued_date', 'is_returned') // guest_accessory Colums
                    ->withTimestamps();
    }
}
