<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Cart;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
 public function checkVoucher(Request $request)
    {
        $voucherCode = $request->input('voucher_code');
        $voucher = Voucher::where('code', $voucherCode)->where('is_used', false)->first();

        if ($voucher) {
            // Kode voucher valid, lakukan tindakan yang sesuai
            // Misalnya, tampilkan informasi voucher atau simpan nilai voucher dalam session
            session(['voucher_code' => $voucherCode]);

            // Redirect ke halaman checkout atau halaman lain yang diinginkan
            return redirect()->route('checkout')->withSuccess('Voucher applied successfully!');
        } else {
            // Kode voucher tidak valid, berikan pesan kesalahan
            return redirect()->back()->withError('Invalid voucher code. Please try again.');
        }
    }

}
