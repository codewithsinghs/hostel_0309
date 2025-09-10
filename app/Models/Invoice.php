<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'guest_id', 'resident_id',
        'invoice_number', 'invoice_date', 'due_date',
        'total_amount', 'paid_amount', 'remaining_amount',
        'status', // pending, unpaid, partial, paid, cancelled
        'remarks'
    ];

    // Relationships
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}