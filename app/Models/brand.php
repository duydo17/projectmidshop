<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    protected $fillable = [       
        'name',
        'user_id',        
    ];
    public function users(){
        return $this->belongsTo(User::class);
    }
    public function categories()
  {
    return $this->belongsToMany(Brand::class, 'category_brand', 'category_id', 'brand_id');
  }
}
