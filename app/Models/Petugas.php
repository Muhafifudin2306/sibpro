<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = "petugas";
    protected $fillable = [
        'name',
        'signature',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'class_id');
    }
}
