<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
       
        'name',
        'user_id',
        
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
    public function brands()
    {
        return $this->belongsToMany(Category::class, 'category_brand', 'brand_id', 'category_id');
    }
    function products(){
        return $this->hasMany(Product::class);
    }
}
