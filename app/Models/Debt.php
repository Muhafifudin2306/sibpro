<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $table = "debts";

    protected $fillable = [
        'is_paid',
        'description',
        'due_date',
        'debt_amount',
        'attribute_id',
        'year_id',
        'vendor_id',
        'image_url'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
