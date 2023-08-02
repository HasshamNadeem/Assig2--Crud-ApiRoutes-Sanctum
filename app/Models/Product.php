<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'user_id',
    ];

    //Accessor function is define below
    // public function getNameAttribute($value)
    // {
    //     return ucfirst($value);
    // }

    // public function getPriceAttribute($value)
    // {
    //     return 'PKR '.$value;
    // }

    // //Mutator is  implemented below

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = 'new. '.$value;
    // }

    // Eloquent model realtionship is as below:
    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user')->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
