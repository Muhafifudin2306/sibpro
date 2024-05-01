<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalSpending extends Model
{
    protected $table = "external_spendings";

    protected $fillable = [
      'spending_date',
      'spending_desc',
      'spending_type',
      'spending_price',
      'is_operational',
      'year_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

}
