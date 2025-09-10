<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'item_type', // fee | accessory | other
        'description', 'price', 'total_amount',
        'from_date', 'to_date','item_id'
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'item_id');
    }
}
