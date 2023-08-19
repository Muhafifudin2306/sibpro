<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $table = "student_classes";
    protected $fillable = [
        'class_name',
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

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
