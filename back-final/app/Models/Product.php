<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ["title", "description", "price", "image"];

    public function users(){
        return $this->belongsToMany(User::class, 'user_id', 'product_id', 'commandes')->withPivot('price', 'quantity');
    }
}
