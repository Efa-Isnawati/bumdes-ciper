<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use ConsoleTVs\Charts\Facades\Charts;
use Charts;
use App\Models\Transaction;


class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah pelanggan (customer)
        $customer = User::count();

        // Menghitung total pendapatan (revenue) dari seluruh transaksi
        $revenue = Transaction::sum('total_price');

        // Menghitung total transaksi yang telah dilakukan
        $transaction = Transaction::count();

        // Mendapatkan tanggal dan waktu saat ini
        $currentDate = now();

        // Array kosong untuk menyimpan label bulan
        $labels = [];

        // Array kosong untuk menyimpan data transaksi per bulan
        $data = [];

        // Mengambil total transaksi untuk 12 bulan terakhir (termasuk bulan saat ini)
        for ($i = 0; $i < 12; $i++) {
            // Mendapatkan nama bulan lengkap (contoh: Januari, Februari)
            $month = $currentDate->format('F');

            // Menghitung total transaksi untuk bulan yang sedang diproses
            $totalTransactions = Transaction::whereYear('created_at', $currentDate->year)
                ->whereMonth('created_at', $currentDate->month)
                ->count();

            // Menyimpan nama bulan dan total transaksi ke dalam array
            $labels[] = $month;
            $data[] = $totalTransactions;

            // Pindah ke bulan sebelumnya untuk iterasi selanjutnya
            $currentDate->subMonth();
        }

        // Menampilkan view dashboard dengan data-data yang diperlukan
        return view('pages.admin.dashboard', [
            'customer' => $customer,
            'revenue' => $revenue,
            'transaction' => $transaction,
            'labels' => json_encode(array_reverse($labels)), // Konversi label ke format JSON
            'data' => json_encode(array_reverse($data)), // Konversi data ke format JSON
        ]);
    }


// public function showTransactionsChart()
//     {
//         $currentDate = now(); // Get the current date and time
//         $labels = []; // Initialize an empty array for labels
//         $data = []; // Initialize an empty array for data

//         // Get the total transactions for the last 12 months (including the current month)
//         for ($i = 0; $i < 12; $i++) {
//             $month = $currentDate->format('F'); // Get the full month name
//             $totalTransactions = Transaction::whereYear('created_at', $currentDate->year)
//                 ->whereMonth('created_at', $currentDate->month)
//                 ->count();

//             $labels[] = $month;
//             $data[] = $totalTransactions;

//             // Move to the previous month
//             $currentDate->subMonth();
//         }

//         return view('pages.admin.dashboard', compact('labels', 'data'));
//     }

}
