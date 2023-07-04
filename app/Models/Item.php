<?php

namespace App\Models;

use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'manual', 'photo', 'is_photo', 'capital'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'item_id', 'id');
    }
}
