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
}
