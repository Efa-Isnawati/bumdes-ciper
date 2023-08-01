<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
// use App\Models\Review;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Barryvdh\DomPDF\Facade as PDF;
use PDF;

class ReviewController extends Controller
{
public function index()
{
    $reviews = Review::with('user', 'product')->get();

    return view('pages.admin.reviews.index', compact('reviews'));
    // $reviews = Review::with('user', 'product')->get();
//   if (request()->ajax()) {
//         $query = Review::with('user', 'product')->get();

//         return Datatables::of($query)
//             ->addColumn('action', function ($item) {
//                 return '';
//             })
//             ->rawColumns(['action'])
//             ->make(true);
// }

//     return view('pages.admin.reviews.index');
}
public function print()
{
    $reviews = Review::with('user', 'product')->get();

    $pdf = PDF::loadView('pages.admin.reviews.print', compact('reviews'));

    return $pdf->download('reviews.pdf');
}


}
