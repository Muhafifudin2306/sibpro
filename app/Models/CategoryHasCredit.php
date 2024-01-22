<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHasCredit extends Model
{
    protected $table = 'category_has_credit';

    protected $fillable = [
        'category_id',
        'credit_id'    
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }
}
