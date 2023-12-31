<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalIncome extends Model
{
    protected $table = 'external_incomes';

    protected $fillable = [
        'pos_id',
        'income_desc',
        'income_period',
        'income_price',
        'year_id'
    ];

    public function pos()
    {
        return $this->belongsTo(PointOfSales::class, 'pos_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

}
