<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'uuid',
        'slug',
        'category_name'
    ];
    
    public function users()
    {
        return $this->hasMany(User::class, 'category_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_has_attribute');
    }
    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'category_has_credit');
    }

    public function enrollments()
    {
        return $this->belongsToMany(Attribute::class, 'user_has_attribute');
    }
}
