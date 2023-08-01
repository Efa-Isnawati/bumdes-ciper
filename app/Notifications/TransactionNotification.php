<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $productId;
    protected $totalPrice;

    /**
     * Create a new notification instance.
     */
    public function __construct($productId, $totalPrice)
    {
        $this->productId = $productId;
        $this->totalPrice = $totalPrice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $product = Product::find($this->productId);
        return (new MailMessage)
                    ->subject('Notifikasi Transaksi')
                    ->line('Terjadi transaksi baru.')
                    ->line('Pesenan Sedang diproses.')
                    ->line('Untuk produk: ' . $product->name)
                    ->line('Jumlah harga: Rp' . $this->totalPrice)
                    ->line('Terima kasih atas pembelian Anda.');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toDatabase(object $notifiable): array
    // {
    //     return [
    //         'message' => 'Selamat, Anda telah melakukan lebih dari 2x transaksi dan mendapatkan merchandise Bumdes Store',
    //     ];
    // }
}
