<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable=['name','code','config','stock','price','description','user_id','brand_id','category_id'];
    function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }
    function product_thumbnail(){
        return $this->hasMany(Product_thumbnail::class,'product_id');
    }
}
