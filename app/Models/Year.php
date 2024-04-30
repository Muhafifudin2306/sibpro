<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute;

class Year extends Model
{
    protected $table = "years";

    protected $fillable = [
        'year_name',
        'year_status',
        'user_id'
    ];
}
