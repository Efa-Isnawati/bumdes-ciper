<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();
    // Fetch and pass the reviews for the product to the view
    $reviews = $product->reviews;
    

    return view('reviews.index', compact('product', 'reviews'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input ulasan
    $validatedData = $request->validate([
        'product_id' => 'required',
        // 'user_id' => 'required',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'required',
    ]);
    $validatedData['user_id'] = auth()->id(); // Menggunakan ID pengguna yang sedang login saat ini
    // Buat ulasan baru
    $review = new Review();
    $review->product_id = $validatedData['product_id'];
    $review->user_id = $validatedData['user_id'];
    $review->rating = $validatedData['rating'];
    $review->comment = $validatedData['comment'];
    $review->save();

    // Redirect ke halaman detail produk atau halaman ulasan terkait
    return redirect()->back()->with('success', 'Review has been submitted.');
}

    /**
     * Display the specified resource.
     */
    public function show($productID, $userID,$reviewID)
{
    // Retrieve the product based on the given product ID
    $product = Product::findOrFail($productID);
    $user = User::findOrFail($userID);


    // Retrieve the review based on the given review ID
    $review = Review::findOrFail($reviewID);

    return view('pages.detail', compact('product', 'review'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
