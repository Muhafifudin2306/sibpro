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
      'year_id'  
    ];

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }
}
