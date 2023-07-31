<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\category;
use App\Models\slider;
use App\Models\product;
use App\Models\brand;
use App\Models\cate_brand;
use App\Models\comment;
use App\Models\product_thumbnail;

use Illuminate\Http\Request;

class IndexUserController extends Controller
{
    function home(){
        $sliders = DB::table('sliders')->orderby('id','desc')->limit(4)->get();
        $categories = Category::all();
        $products = Product::all();
        $brands = Brand::all();
        $cate_brands = Cate_brand::all();
        $product_thumbnail = Product_thumbnail::all();
        
        $newproducts = Product::orderby('id','desc')->limit(5)->get();         
       
     
     return view('users.pages.home',compact('sliders','categories','products','newproducts' ,'brands','cate_brands','product_thumbnail'));
    }
    function product(Request $request){
        $cate_id = $request->query('cate');
        $brand_id = $request->query('brand');
        if($request->has('cate') && $request->has('brand')){
            $products = Product::where('category_id', $cate_id)
                   ->where('brand_id', $brand_id)
                   ->get();
        }       
        else if($request->has('cate')){
            $products = Category::find($cate_id)->products;
           
        }        
        else{
            $products = Product::all();
        }
        
        $brands = Brand::all();
        $cate_brands = Cate_brand::all();
        $categories = Category::all();
         return view('users.pages.product',compact('products','categories','brands','cate_brands'));
    }
    function product_detail($id){
        
        $brands = Brand::all();
        $cate_brands = Cate_brand::all();
        $categories = Category::all();
         $product = Product::find($id);
         $comments = Comment::all();
      
        return view('users.pages.product_detail',compact('product','categories','brands','cate_brands','comments'));
    }
    function add_comment(Request $request, $id){
        $request->validate(
            [
                'email' => 'required',
                'content' => 'required',                
            ],
            [
                'required' => ':attribute không được để trống',
                
            ],
            [
                'email' => 'Email',
                'content' => 'Nội Dung',               

            ]
        );
        $email = $request->input('email');
        $content = $request->input('cmt');
        $product_id = $id;
        
        Comment::create(['email'=>$email, 'content'=>$content,'product_id'=>$product_id]);
        return redirect()->back();

    }
}
