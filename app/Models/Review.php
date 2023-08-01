<?php

namespace App\Models;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function reviews()
{
    return $this->hasMany(Review::class, 'product_id');
}
protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function user()
{
    return $this->belongsTo(User::class);
}


public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

    use HasFactory;
}

