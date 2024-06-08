<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $table = 'bahans';

    protected $fillable = [
        'spending_date',
        'spending_desc',
        'increment',
        'year_id',
        'invoice_number',
        'spending_price',
        'image_url'
    ];
}
