<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryHead extends Model
{
    protected $table = 'accessories_heads';

    use HasFactory;

    protected $fillable = [
        'name',
        'university_id',
        'created_by',
        'university_id',
    ];
    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }
    public function studentAccessories()
    {
        return $this->hasMany(StudentAccessory::class);
    }

    

}
