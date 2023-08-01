<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductGallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;

class DashboardProductController extends Controller
{
    public function index()
    {
       $products = Product::with(['galleries','category'])->get();

        return view('pages.dashboard-products',[
            'products' => $products
        ]);
    }
    public function search(Request $request)
{
     $categories = Category::take(6)->get();
     $products = Product::with('galleries')->take(20)->get();
        
        // return view('pages.home',[
        //     'categories' => $categories,
        //     'products' => $products
        // ]);
    $query = $request->input('query');

    // Lakukan pencarian berdasarkan query
    $products = Product::where('name', 'like', "%$query%")->get();

    // return view('search', compact('products'));
            return view('search',[
            'categories' => $categories,
            'products' => $products
        ]);
}

    public function details(Request $request, $id)
    {
        $product = Product::with(['galleries','user','category'])->findOrFail($id);
        $categories = Category::all();

        return view('pages.dashboard-products-details',[
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

    public function create()
    {
        $users = User::all();
        $categories = Category::all();

        return view('pages.dashboard-products-create',[
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

        return redirect()->route('dashboard-product');
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('dashboard-product');
    }
}
