<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'manual', 'photo', 'is_photo', 'capital', 'category_id'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'item_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
