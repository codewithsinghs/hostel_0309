<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryHead extends Model
{
    protected $table = 'accessory_heads';

    use HasFactory;

    protected $fillable = [
        'name',
        'university_id',
        'created_by',
        'university_id',
    ];
    // public function accessories()
    // {
    //     return $this->hasMany(Accessory::class, 'accessories_heads_id');
    // }
    public function studentAccessories()
    {
        return $this->hasMany(StudentAccessory::class);
    }

    public function guestAccessories()
    {
        return $this->hasMany(GuestAccessory::class, 'accessories_heads_id');
    }

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'guest_accessories', 'accessories_heads_id', 'guest_id')
            ->withPivot('price', 'total_amount', 'from_date', 'to_date')
            ->withTimestamps();
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }


    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }
}
