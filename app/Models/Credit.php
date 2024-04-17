<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';

    protected $fillable = [
        'slug',
        'credit_name',
        'credit_price',
        'credit_type',
        'semester'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_has_credit');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_credit');
    }

    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'category_has_credit', 'category_id');
    }
}
