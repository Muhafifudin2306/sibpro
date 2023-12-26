<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Year;
use App\Models\User;
use App\Models\Category;


class Attribute extends Model
{
    protected $table = "attributes";

    protected $fillable = [
        'attribute_name',
        'attribute_price',
        'attribute_type',
        'vendor_id',
        'slug'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_has_attribute');
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_has_attribute', 'category_id');
    }
    public function vendors()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
