<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\Category;
use Exception;
use Midtrans\Snap;
use App\Models\Transaction;
use Midtrans\Config;
use App\Models\TransactionDetail;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // Simpan data pengguna
        $user = Auth::user();
        $user->update($request->except(['total_price', 'shipping_price']));
                $carts = Cart::with(['product.galleries', 'user'])->where('users_id', Auth::user()->id)->get();
        
        $discountAmount = 0;
        $totalPrice = 0;
        $productCategories = '';
        // $totalPriceWithDiscount=0;
                // Mendapatkan kategori produk dari session atau form
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price;
            $productCategories = Category::where('id', $cart->product->categories_id)->get();
        }

        // Proses kode voucher
        // $voucherCode = $request->input('voucher_code');

        // Logika biaya pengiriman
        $shippingPrice = 0; 
        $productCategory = $productCategories != null || $productCategories != '' ? $productCategories[0]['name'] : '';
        // dd($totalPriceWithDiscount);

        if ($productCategory === 'makanan') {
            $shippingPrice = 0; // Gratis ongkir untuk kategori "makanan"
        } else {
            if ($totalPrice >= 100000) {
                $shippingPrice = 0; // Gratis ongkir jika total harga >= 100000
            } else {
                $shippingPrice = 16000; // Ongkir 16000 untuk total harga < 100000
            }
        }

        if ($totalPrice > 60000) {
            $discountAmount = $totalPrice * 0.1; // Menghitung potongan harga 10%
        } else {
            $discountAmount = 0;
        }

        $totalPriceWithDiscount = $shippingPrice + $totalPrice - $discountAmount;
       

        // Menghitung jumlah diskon berdasarkan persentase
        // $discountAmount = $request->total_price * ($discountPercentage / 100);

        // Tandai voucher sebagai digunakan
        // $voucher->is_used = true;
        // $voucher->save();

        // Hitung total harga dengan potongan
        
        // $shippingPrice =  $request->shipping_price ?? 0;
        
//  dd($totalPriceWithDiscount);
        try {
            // Proses checkout
            $code = 'STORE-' . mt_rand(0000, 9999);
            $carts = Cart::with(['product', 'user'])
                ->where('users_id', Auth::user()->id)
                ->get();

            // $shipping_price = 0; // Inisialisasi biaya pengiriman dengan nilai awal

            // // Lakukan logika atau aturan untuk menghitung biaya pengiriman berdasarkan data yang relevan, misalnya berat paket, jarak pengiriman, atau metode pengiriman.
            // // Contoh:
            // if ($totalPriceWithDiscount > 100000) {
            //     $shipping_price = 0; // Biaya pengiriman sebesar $10 jika total harga melebihi $100
            // } else {
            //     $shipping_price = 16000; // Biaya pengiriman sebesar $5 untuk total harga di bawah $100
            // }

            $transaction = Transaction::create([
                'users_id' => Auth::user()->id,
                'inscurance_price' => 0,
                'shipping_price' => $shippingPrice,
                'total_price' => $totalPriceWithDiscount,
                'transaction_status' => 'PENDING',
                'code' => $code
            ]);

            foreach ($carts as $cart) {
                $trx = 'TRX-' . mt_rand(0000, 9999);

                TransactionDetail::create([
                    'transactions_id' => $transaction->id,
                    'products_id' => $cart->product->id,
                    'price' => $cart->product->price,
                    'shipping_status' => 'PENDING',
                    'resi' => '',
                    'code' => $trx
                ]);
            }

            // Delete cart data
            Cart::with(['product', 'user'])
                ->where('users_id', Auth::user()->id)
                ->delete();

            $user->notify(new TransactionNotification($cart->product->id, $cart->product->price));

            // Konfigurasi Midtrans
            Config::$serverKey = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized = config('services.midtrans.isSanitized');
            Config::$is3ds = config('services.midtrans.is3ds');

            // Buat array untuk dikirim ke Midtrans
            $midtrans = [
                'transaction_details' => [
                    'order_id' =>  $code,
                    'gross_amount' => (int) $totalPriceWithDiscount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'enabled_payments' => ['gopay', 'bank_transfer'],
                'vtweb' => []
            ];

            // Ambil halaman pembayaran Midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // Redirect ke halaman pembayaran Midtrans
            return redirect($paymentUrl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkoutWithVoucher(Request $request)
    {
        // Validasi data lainnya

        // Proses kode voucher
        // $voucherCode = $request->input('voucher_code');
        // $discountAmount = 0;

        // if ($voucherCode) {
        //     // Ambil data voucher berdasarkan kode voucher
        //     $voucher = Voucher::where('code', $voucherCode)->where('is_used', false)->first();
        //     if ($voucher) {
        //         $discountAmount = $voucher->amount;

        //         // Tandai voucher sebagai digunakan
        //         $voucher->is_used = true;
        //         $voucher->save();
        //     }
        // }

        // // Hitung total harga dengan potongan
        // $totalPrice = $request->total_price ?? 0;
        // $totalPriceWithDiscount = $totalPrice - $discountAmount;

        // // Mendapatkan kategori produk dari session atau form
        // $productCategory = $request->input('product_category');

        // // Logika biaya pengiriman
        // $shippingPrice = 0;

        // if ($productCategory === 'makanan') {
        //     $shippingPrice = 0; // Pengiriman gratis untuk kategori "makanan"
        // } else {
        //     $totalPrice = $request->total_price ?? 0;
        //     if ($totalPrice >= 100000) {
        //         $shippingPrice = 0; // Gratis ongkir jika total harga >= 100000
        //     } else {
        //         $shippingPrice = 16000; // Ongkir 16000 untuk total harga < 100000
        //     }
        // }

        // Simpan informasi kode voucher dalam session atau variabel lainnya
        // session(['voucher_code' => $voucherCode]);

        // try {
        //     // Proses penyimpanan data ke database

        //     $transaction = new Transaction();
        //     $transaction->total_price = $totalPriceWithDiscount;
        //     // Simpan data lainnya

        //     $transaction->save();

        //     // Redirect atau kembali ke halaman lain

        // } catch (Exception $e) {
        //     // Tangani kesalahan jika terjadi

        //     return back()->withError($e->getMessage());
        // }
    }

    public function callback(Request $request)
    {
        // Set konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Buat instance midtrans notification
        $notification = new Notification();

        // Assign ke variable untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($order_id);

        // Handle notification status midtrans
        if ($status == 'capture') {
            if ($type == 'credit_card'){
                if($fraud == 'challenge'){
                    $transaction->status = 'PENDING';
                }
                else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }
        else if ($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }
        else if($status == 'pending'){
            $transaction->status = 'PENDING';
        }
        else if ($status == 'deny') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'expire') {
            $transaction->status = 'CANCELLED';
        }
        else if ($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }

        // Simpan transaksi
        $transaction->save();

        // Kirimkan email
        if ($transaction) {
            if ($status == 'capture' && $fraud == 'accept') {
                //
            } else if ($status == 'settlement') {
                //
            } else if ($status == 'success') {
                //
            } else if ($status == 'capture' && $fraud == 'challenge') {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment Challenge'
                    ]
                ]);
            } else {
                return response()->json([
                    'meta' => [
                        'code' => 200,
                        'message' => 'Midtrans Payment not Settlement'
                    ]
                ]);
            }

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Notification Success'
                ]
            ]);
        }
    }
}
