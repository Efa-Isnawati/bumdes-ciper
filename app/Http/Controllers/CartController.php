<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
   $carts = Cart::with(['product.galleries', 'user'])->where('users_id', Auth::user()->id)->get();
   $user = Auth::user();

        $totalPrice = 0;
        $productCategories = '';

        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price;
            $productCategories = Category::where('id', $cart->product->categories_id)->get();
        }
        

        $shippingPrice = 0;
        $productCategory = $productCategories != null || $productCategories != '' ? $productCategories[0]['name'] : '';

        // Logika biaya pengiriman
        if ($productCategory !== 'makanan') {
            if ($totalPrice >= 100000) {
                $shippingPrice = 0; // Gratis ongkir jika total harga >= 100000
            } else {
                $shippingPrice = 16000; // Ongkir 16000 untuk total harga < 100000
            }
        }

        return view('pages.cart', [
            'user' => $user,
            'carts' => $carts,
            'totalPrice' => $totalPrice,
            'productCategory' => $productCategory,
            'shippingPrice' => $shippingPrice,
        ]);
    }

    public function delete(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        $cart->delete();

        return redirect()->route('cart', ['productCategory' => 'makanan']);
    }
    
    public function success()
    {
        return view('pages.success');
    }
}