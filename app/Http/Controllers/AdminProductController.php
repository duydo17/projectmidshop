<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\category;
use App\Models\brand;
use App\Models\product;
use App\Models\User;
use App\Models\slider;
use App\Models\comment;
use App\Models\product_thumbnail;
use Illuminate\Http\Request;


class AdminProductController extends Controller
{
    public function index()
    {


        return view('admin.pages.products.list_post');
    }
    //Start CATEGORY
    function list_category()
    {
        $categories = Category::paginate(5);
        $users = User::all();
        return view('admin.pages.products.list_category', compact('categories', 'users'));
    }
    function create_category()
    {
        return view('admin.pages.products.create_category');
    }
    function add_category(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ],
            [
                'required' => ':attribute bắt buộc nhập',
            ],
            [
                'name' => 'Tên Danh Mục'
            ]
        );
        $name = $request->input('name');
        $user_id = $request->session()->get('id');


        Category::create(['name' => $name, 'user_id' => $user_id]);
        return redirect()->route('list.category')->with('success', 'Thêm Danh Mục Thành Công');;
    }
    function delete_category($id)
    {
        $cate = Category::find($id);
        $cate->delete();
        return redirect()->route('list.category');
    }

    // Start Brand

    function list_brand()
    {
        $brands = Brand::paginate(5);
        $users = User::all();
        return view('admin.pages.products.list_brand', compact('brands', 'users'));
    }
    function create_brand()
    {
        return view('admin.pages.products.create_brand');
    }
    function add_brand(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ],
            [
                'required' => ':attribute bắt buộc nhập',
            ],
            [
                'name' => 'Tên Thương Hiệu'
            ]
        );
        $name = $request->input('name');
        $user_id = $request->session()->get('id');
        Brand::create(['name' => $name, 'user_id' => $user_id]);
        return redirect()->route('list.brand')->with('success', 'Thêm Thương Hiệu Thành Công');
    }
    function delete_brand($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('list.brand');
    }

    //start Product
    function list_product()
    {
        $products = Product::paginate(5);
        $users = User::all();
        $cates = Category::all();
        $brands = Brand::all();
        return view('admin.pages.products.list_product', compact('products', 'users', 'cates', 'brands'));
    }
    function create_product()
    {
        $cates = Category::all();
        $brands = Brand::all();
        return view('admin.pages.products.create_product', compact('cates', 'brands'));
    }
    function add_product(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'code' => 'required',
                'stock' => 'required',
                'price' => 'required|numeric',
                'description' => 'required',
                'config' => 'required',
                'file.*' => 'bail|required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => ':attribute phải là số'
            ],
            [
                'name' => 'Tên sản phẩm',
                'code' => 'Mã sản phẩm',
                'stock' => 'Số lượng',
                'price' => 'Giá',
                'description' => 'Chi tiết',
                'config' => 'Mô tả ngắn',

            ]
        );
        $product = new Product();
        $product->name = $request->input('name');
        $product->code = $request->input('code');
        $product->stock = $request->input('stock');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->config = $request->input('config');
        $product->category_id = $request->input('category');
        $product->brand_id = $request->input('brand');
        $product->user_id = $request->session()->get('id');
        $product->save();

        if ($request->hasFile("file")) {
            foreach ($request->file("file") as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path("uploads");
                $image->move($destinationPath, $filename);
                $imagePath = "uploads/" . $filename;
                $product_thumbnail = new Product_thumbnail();
                $product_thumbnail->image = $imagePath;
                $product_thumbnail->product_id = $product->id;
                $product_thumbnail->save();
            }
        }
        return redirect()->route('list.product')->with('success', 'Thêm Sản Phẩm Thành Công');
    }
    function delete_product($id)
    {
        $product = Product::find($id);
        if ($product->product_thumbnail->count() > 0) {
            foreach ($product->product_thumbnail as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
                $image->delete();
            }
        
        }
        $product->delete();
        return redirect()->route('list.product')->with('success', 'Xóa Sản Phẩm Thành Công');
    }
    function update_product($id)
    {
        $product = Product::find($id);
        $cates = Category::all();
        $brands = Brand::all();
        return view('admin.pages.products.update_product', compact('product', 'cates', 'brands'));
    }
    function edit_product(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'code' => 'required',
                'stock' => 'required',
                'price' => 'required|numeric',
                'description' => 'required',
                'config' => 'required',
                'file' => 'bail|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            ],
            [
                'required' => ':attribute không được để trống',
                'numeric' => ':arttribute phải là số'
            ],
            [
                'name' => 'Tên sản phẩm',
                'code' => 'Mã sản phẩm',
                'stock' => 'Số lượng',
                'price' => 'Giá',
                'description' => 'Chi tiết',
                'config' => 'Mô tả ngắn',

            ]
        );

        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->code = $request->input('code');
        $product->stock = $request->input('stock');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->config = $request->input('config');
        $product->category_id = $request->input('category');
        $product->brand_id = $request->input('brand');
        $product->user_id = $request->session()->get('id');
        $product->save();
        if ($request->hasFile("file")) {
            //delete old images if they existed
            if ($product->product_thumbnail->count() > 0) {
                foreach ($product->product_thumbnail as $image) {
                    if (File::exists($image->image)) {
                        File::delete($image->image);
                    }
                    $image->delete();
                }
            }
            foreach ($request->file("file") as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path("uploads");
                $image->move($destinationPath, $filename);
                $imagePath = "uploads/" . $filename;
                $product_thumbnail = new Product_thumbnail();
                $product_thumbnail->image = $imagePath;
                $product_thumbnail->product_id = $product->id;
                $product_thumbnail->save();
            }
        }


        return redirect()->route('list.product')->with('success', 'Update Sản Phẩm Thành Công');
    }
    //product_image
    function add_thumbnail()
    {
        $products = Product::all();
        return view('admin.pages.products.create_product_thumbnail', compact('products'));
    }
    function add_image(Request $request)
    {
        $products = Product::all();

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $filename = time() . '.' . $file->getClientOriginalName();
                $file->move('uploads', $filename);
                $files[]['image'] = $filename;
            }
        }

        $user_id = $request->session()->get('id');
        $product_id = $request->input('product_id');
        foreach ($files as $file) {
            Product_thumbnail::create(['image' => $file['image'], 'product_id' => $product_id, 'user_id' => $user_id]);
        }

        return back()
            ->with('success', 'You have successfully upload file.');
    }

    //slider
    function list_slider()
    {
        $users = User::all();
        $sliders = Slider::paginate(5);
        return view('admin.pages.slider.list', compact('sliders', 'users'));
    }
    function create_slider()
    {
        return view('admin.pages.slider.create');
    }
    function add_slider(Request $request)
    {
        $user_id = $request->session()->get('id');
        $name = $request->input('name');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->move('sliders', $filename);
            $thumbnail = $filename;
        }

        Slider::create(['name' => $name, 'thumbnail' => $thumbnail, 'user_id' => $user_id]);
        return redirect()->route('list.slider')->with('success', 'Thêm Slide Thành Công');
    }
    function delete_slider($id)
    {
        $slider = Slider::find($id);
        $slider->delete();
        return redirect()->route('list.slider')->with('success', 'Xóa Slide Thành Công');
    }
    //comment
    function list_comment()
    {
        $products = Product::all();
        $comments = Comment::all();
        return view('admin.pages.products.list_comment', compact('comments', 'products'));
    }
    function delete_comment($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return redirect()->route('list.comment')->with('success', 'Xóa Ý Kiến Thành Công');
    }
}
