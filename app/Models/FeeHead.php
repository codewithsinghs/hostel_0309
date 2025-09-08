<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeHead extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'name',
        'university_id',
        'created_by',
    ];


    public function fee()
    {
        return $this->hasMany(Fee::class);
    }
}
