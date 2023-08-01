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
            // Loop through each cart item to calculate the total price of the cart
            $totalPrice = 0;
            foreach ($user->cartItems as $item) {
                $totalPrice += $item->product->price * $item->quantity;
            }

            // Check the first cart item to get the product ID for the CartReminder notification
            $firstCartItem = $user->cartItems->first();
            $productId = $firstCartItem ? $firstCartItem->products_id : null;

            // Create the CartReminder notification instance with the correct arguments
            $notification = new CartReminder($productId, $totalPrice);

            // Send the notification to the user
            Notification::send($user, $notification);
        }
    }
}



