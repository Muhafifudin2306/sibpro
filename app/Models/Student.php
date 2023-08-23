<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'student_name',
        'nis',
        'class_id',
        'user_id',
        'category_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function classes()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
