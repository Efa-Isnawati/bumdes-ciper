<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardSettingController extends Controller
{
    public function store()
    {
        $user = Auth::user();
        $categories = Category::all();

        return view('pages.admin.dashboard-settings',[
            'user' => $user,
            'categories' => $categories
        ]);
    }

    // public function account()
    // {
    //     $user = Auth::user();

    //     return view('pages.dashboard-account',[
    //         'user' => $user
    //     ]);
    // }

    public function update(Request $request, $redirect)
    {
        $data = $request->all();

        $item = Auth::user();

        $item->update($data);

        return redirect()->route($redirect);
    }
}
