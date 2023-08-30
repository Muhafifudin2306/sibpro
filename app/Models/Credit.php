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
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_has_credit');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_has_credit');
    }

    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'category_has_credit', 'category_id');
    }
}
