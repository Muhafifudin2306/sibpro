<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';

    protected $fillable = [
        'credit_name',
        'credit_price',
        'semester',
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
