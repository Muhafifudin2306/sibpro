<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $table = "student_classes";
    protected $fillable = [
        'class_name',
        'user_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'class_id');
    }
}
