<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $table = "years";

    protected $fillable = [
        'year_name',
        'year_status'
    ];
}
