<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\User;

class Attribute extends Model
{
    protected $table = "attributes";

    protected $fillable = [
        'attribute_name',
        'attribute_price',
        'year_id',
        'user_id'
    ];

    public function years()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
