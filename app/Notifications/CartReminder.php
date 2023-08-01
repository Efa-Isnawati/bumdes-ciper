<?php

namespace App\Notifications;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
use App\Models\Cart;

class CartReminder extends Notification
{
    protected $productId;
    protected $totalPrice;

    public function __construct($productId, $totalPrice)
    {
        $this->productId = $productId;
        $this->totalPrice = $totalPrice;
    }

    public function toMail($notifiable)
    {
        // Calculate the discount and get cart items
        $discount = 0;
        $price = 60000; // Batas harga untuk mendapatkan diskon
        $cartItems = $notifiable->cartItems;

        foreach ($cartItems as $item) {
            $discount += $item->product->price * $item->quantity;
        }

        // Determine if the user is eligible for the discount
        if ($discount > $price) {
            $discountAmount = $discount * 0.1; // Diskon 10%
            $discountMsg = "Jika Anda segera checkout, Anda akan mendapatkan diskon sebesar " . number_format($discountAmount, 0, ',', '.') . "!";
        } else {
            $discountMsg = "Checkout segera untuk mendapatkan kesempatan diskon!";
        }

        // Calculate the total price of the cart
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        // Retrieve the product from the cart by product ID using the Product model
        $product = optional(Product::find($this->productId));

        // Get the product name or use a fallback if the product is not found
        $productName = $product ? $product->name : 'Unknown Product';

        // Compose the email notification
        return (new MailMessage)
            ->subject('Ayo segera checkout produk dikeranjang anda')
            ->line('Halo,')
            ->line('Anda memiliki produk dalam keranjang belanja yang telah ada selama 1 hari.')
            ->line('Sebagai informasi, total harga produk dalam keranjang Anda adalah Rp. ' . number_format($item->product->price, 0, ',', '.') . '.')
            ->line('Untuk produk: ' . $productName)
            ->line($discountMsg)
            ->action('Checkout Sekarang', route('checkout'))
            ->line('Terima kasih telah berbelanja di toko kami!');
    }

    public function via($notifiable)
    {
        return ['mail']; // Send the notification through the "mail" channel
    }
}
