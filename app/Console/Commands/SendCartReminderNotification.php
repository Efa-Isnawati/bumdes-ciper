<?php

namespace App\Console\Commands;

use App\Notifications\CartReminder;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendCartReminderNotification extends Command
{
    protected $signature = 'notification:send_cart_reminder';
    protected $description = 'Send cart reminder notification to users with items in cart for 1 day.';

    public function handle()
    {
        $users = User::with('cartItems.product')
            ->whereHas('cartItems', function ($query) {
                $query->where('created_at', '<=', now()->subDay());
            })
            ->get();

        foreach ($users as $user) {
            foreach ($user->cartItems as $cartItem) {
                // Calculate the total price of the cart item
                $totalPrice = $cartItem->product->price * $cartItem->quantity;

                // Get the product ID for the CartReminder notification
                $productId = $cartItem->product->id;

                // Create the CartReminder notification instance with the correct arguments
                $notification = new CartReminder($productId, $totalPrice);

                // Send the notification to the user
                Notification::send($user, $notification);
            }
        }
    }
}




