<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $table = "student_classes";
    protected $fillable = [
        'class_name'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
