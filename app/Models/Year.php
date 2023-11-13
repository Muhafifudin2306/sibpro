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

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'id_year');
    }

    public function class()
    {
        return $this->hasMany(StudentClass::class, 'id_year');
    }
}
