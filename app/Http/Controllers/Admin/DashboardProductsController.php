<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductGallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Support\Facades\Auth;


class DashboardProductsController extends Controller
{
    public function index()
    {
       $products = Product::with(['galleries','category'])->get();

        return view('pages.admin.product.index',[
            'products' => $products
        ]);
    }

    public function details(Request $request, $id)
    {
        $product = Product::with(['galleries','user','category'])->findOrFail($id);
        $categories = Category::all();

        return view('pages.admin.product.edit',[
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function uploadGallery(Request $request)
    {
        $data = $request->all();

        $data['photos'] = $request->file('photos')->store('assets/product', 'public');

        ProductGallery::create($data);

        return redirect()->route('dashboard-product-details', $request->products_id);
    }

    public function deleteGallery(Request $request, $id)
    {
        $item = ProductGallery::findorFail($id);
        $item->delete();

        return redirect()->route('dashboard-product-details', $item->products_id);
    }
        public function delete(Request $request, $id)
    {
        $data = Product::findorFail($id);
        $data->delete();

        return redirect()->route('product', $data->products_id);
    }

    public function create()
    {
        $users = Auth::user()->roles =='ADMIN';
        $categories = Category::all();

        return view('pages.admin.product.create',[
            'users' => $users,
            'categories' => $categories
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        $product = Product::create($data);

        $gallery = [
            'products_id' => $product->id,
            'photos' => $request->file('photo')->store('assets/product', 'public')
        ];
        ProductGallery::create($gallery);

        return redirect()->route('product');
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('product');
    }
}
