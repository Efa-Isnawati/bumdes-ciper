<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
     public function index()
    {
        $sellTransactions = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->whereHas('product', function($product){
                                $product->where('users_id', Auth::user()->id);
                            })->get();
        $buyTransactions = TransactionDetail::with(['transaction.user','product.galleries'])
                            ->whereHas('transaction', function($transaction){
                                $transaction->where('users_id', Auth::user()->id);
                            })->get();
        
        return view('pages.admin.transaction.dashboard-transactions',[
            'sellTransactions' => $sellTransactions,
            'buyTransactions' => $buyTransactions
        ]);
    }

public function details(Request $request, $id)
{
    $transaction = TransactionDetail::with(['transaction.user','product.galleries'])
                        ->findOrFail($id);
    return view('pages.admin.transaction.dashboard-transactions-details', [
        'transaction' => $transaction
    ]);
}


    public function update(Request $request, $id)
    {
        $data = $request->all();

        $item = TransactionDetail::findOrFail($id);

        $item->update($data);

        $user = User::find($item->transaction->users_id);
    // $user->notify(new TransactionNotification);
    // Hitung jumlah transaksi pembelian pengguna
    // $transactionCount = Transaction::where('user_id', auth()->user()->id)
    //     ->where('status', 'completed')
    //     ->count();

    // if ($transactionCount >= 2) {
    //     $user = User::find($item->transaction->users_id);
    //     $user->notify(new TransactionNotification());
    // }

        return redirect()->route('transaction-details', $id);
    }
}
