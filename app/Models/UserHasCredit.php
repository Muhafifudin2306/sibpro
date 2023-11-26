<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasCredit extends Model
{
    protected $table = "user_has_credit";

    protected $fillable = [
        'user_id',
        'credit_id',
        'status',
        'credit_price'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id', 'id');
    }

    public function studentClass()
    {
        return $this->belongsTo(Year::class, 'class_id', 'id');
    }
}
