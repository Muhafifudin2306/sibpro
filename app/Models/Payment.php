<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";

    protected $fillable = [
        'uuid',
        'increment',
        'invoice_number',
        'type',
        'user_id',
        'credit_id',
        'attribute_id',
        'class_id',
        'year_id',
        'status',
        'price'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
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
