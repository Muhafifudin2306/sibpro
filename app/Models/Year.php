<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute;
use App\Models\StudentClass;

class Year extends Model
{
    protected $table = "years";

    protected $fillable = [
        'year_name',
        'year_status',
        'user_id'
    ];

    // One to Many Relation
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'id_year');
    }

    // One to Many Relation
    public function class()
    {
        return $this->hasMany(StudentClass::class, 'id_year');
    }
}
