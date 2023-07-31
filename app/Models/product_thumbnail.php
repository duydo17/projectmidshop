<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_thumbnail extends Model
{
    use HasFactory;
    protected $fillable=['image','user_id','product_id'];
    function products(){
        return $this->belongsTo(Product::class);
    }
}
