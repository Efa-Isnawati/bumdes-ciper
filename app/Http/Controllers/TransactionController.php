<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    public function updateWithNotification(Request $request, $id)
    {
        $data = $request->all();

        $item = TransactionDetail::findOrFail($id);

        $item->update($data);

        // Hitung jumlah transaksi pembelian pengguna
        $transactionCount = Transaction::where('user_id', auth()->user()->id)
            ->where('status', 'completed')
            ->count();

        if ($transactionCount >= 2) {
            $user = User::find($item->transaction->users_id);
            $user->notify(new TransactionNotification());
        }

        return redirect()->route('home', $id);
    }
}
