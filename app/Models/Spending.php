<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $table = "spendings";

    protected $fillable = [
        'spending_date',
        'spending_desc',
        'spending_type',
        'spending_price',
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
}
