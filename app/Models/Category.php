<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\User;
use App\Models\Attribute;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
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

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_has_attribute');
    }
    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'category_has_credit');
    }
    public function attachAttributesWithYear($attributeIds, $yearId)
    {
        $this->attributes()->attach($attributeIds, ['year_id' => $yearId]);
    }
}
