<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TransactionNotification;
use App\Models\User;

class NotificationController extends Controller
{
// public function index()
// {
//  $user = User::find(Auth::id());
//         $user = User::find(Auth::id());
//         $notifications = $user->unreadNotifications;
//         $user->unreadNotifications->markAsRead();

//         return view('notifications.index', compact('notifications'));
// }
// public function sendNotification()
//     {
//         $user = Auth::user();
//         $user->notify(new TransactionNotification());

//         return redirect()->back()->with('success', 'Notification sent successfully.');
//     }
//  public function getNotifications()
//     {
//         $user = Auth::user();
//         $unreadNotifications = $user->unreadNotifications;

//         return response()->json([
//             'unreadNotifications' => $unreadNotifications,
//         ]);
//     }

    public function getNotification() {
        $user = User::find(Auth::id());
        
    }

    public function getNotificationMerchandise() {
        $user = User::find(Auth::id());

        $check_transaksi_user = Transaction::where('users_id', Auth::id())->get();

        return count($check_transaksi_user);
        
    }
}
