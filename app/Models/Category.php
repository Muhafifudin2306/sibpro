<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\User;
use App\Models\Attribute;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
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

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_has_attribute');
    }
}
