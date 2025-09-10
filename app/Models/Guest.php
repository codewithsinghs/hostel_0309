<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ this is important
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Guest extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'scholar_number',
        'name',
        'email',
        'faculty_id',
        'department_id',
        'course_id',
        'gender',
        // 'scholar_number',
        'fathers_name',
        'mothers_name',
        'local_guardian_name',
        'emergency_contact',
        'mobile',
        'parent_contact',
        'guardian_contact',
        'room_preference',
        'fee_waiver',
        'bihar_credit_card',
        'tnsd',
        'remarks',
        'status',
        'months',
        'attachment_path',
        'days',
        'admin_remarks',
        'token',
        'token_expiry',
    ];

    protected $hidden = [
        'updated_at',
        'remember_token',
        'token',
        'token_expiry',
        // add other sensitive or recursive fields
    ];


    // public function accessory()
    // {
    //     return $this->belongsToMany(Accessory::class, 'guest_accessory', 'guest_id', 'accessory_id');
    // }
    // public function accessories()
    // {
    //     return $this->belongsToMany(Accessory::class, 'guest_accessories', 'guest_id', 'accessories_heads_id', 'id', 'accessories_heads_id')
    //         ->withPivot(['price', 'total_amount', 'from_date', 'to_date'])
    //         ->with('accessoryHead');
    // }

    // public function accessories()
    // {
    //     return $this->belongsToMany(Accessory::class, 'guest_accessories', 'guest_id', 'accessory_head_id','id','accessory_head_id')
    //         ->withPivot(['price', 'total_amount', 'from_date', 'to_date'])
    //         ->with('accessoryHead');
    // }


    public function feeException()
    {
        return $this->hasOne(FeeException::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    // 08092025

    // public function invoices()
    // {
    //     return $this->hasMany(Invoice::class);
    // }

    // public function invoiceItems()
    // {
    //     return $this->hasManyThrough(InvoiceItem::class, Invoice::class);
    // }

    // public function accessories()
    // {
    //     return $this->invoiceItems()
    //         ->where('item_type', 'accessory')
    //         ->with('accessory:id,name');
    // }


    //  public function accessories()
    // {
    //     return $this->hasMany(GuestAccessory::class);
    // }

    // public function accessories()
    // {
    //     return $this->hasManyThrough(
    //         Accessory::class,
    //         GuestAccessory::class,
    //         'guest_id',               // Foreign key on GuestAccessory
    //         'accessories_heads_id',  // Foreign key on Accessory
    //         'id',                     // Local key on Guest
    //         'accessories_heads_id'   // Local key on GuestAccessory
    //     );
    // }


    // public function accessoryHeads()
    // {
    //     return $this->belongsToMany(AccessoryHead::class, 'guest_accessories', 'guest_id', 'accessories_heads_id')
    //         ->withPivot('price', 'total_amount', 'from_date', 'to_date')
    //         ->withTimestamps();
    // }

    // public function guestAccessories()
    // {
    //     return $this->hasMany(GuestAccessory::class);
    // }



    // 10092025

    public function accessories()
    {
        return $this->belongsToMany(Accessory::class, 'student_accessories')
            ->withPivot('issued_date', 'is_returned') // guest accesories table column
            ->withTimestamps();
    }
}
