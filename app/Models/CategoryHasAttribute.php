<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHasAttribute extends Model
{
    protected $table = 'category_has_attribute';

    protected $fillable = [
        'category_id',
        'atribute_id'    
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

}
