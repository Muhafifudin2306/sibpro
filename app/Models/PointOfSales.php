<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointOfSales extends Model
{
    protected $table = 'point_of_sales';

    protected $fillable = [
        'point_code',
        'point_source',
        'point_name'
    ];
}
