<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_transaction = Transaction::count('id');
        $total_net_profit = Transaction::sum('net_profit');
        $total_gross_profit = Transaction::sum('total');
        $total_item = Item::count('id');


        $total_gross_profit = number_format($total_gross_profit, 0, ',', '.');
        $total_net_profit = number_format($total_net_profit, 0, ',', '.');
        return view('dashboard.index', ['total_transaction' => $total_transaction, 'total_net_profit' => $total_net_profit, 'total_item' => $total_item, 'total_gross_profit' => $total_gross_profit]);
    }

    public function ajaxChart()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $transaksi = Transaction::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, sum(net_profit) as net_profit')
        ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();



        $formattedTransaksi = collect([]);

        // Menambahkan semua bulan dengan total 0
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $startMonth = $currentMonth - 11;
        $startYear = $currentYear;
        if ($startMonth <= 0) {
            $startMonth += 12;
            $startYear -= 1;
        }

        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::create()->setYear($startYear)->month($startMonth)->format('M Y');
            $transaksiData = $transaksi->first(function ($item) use ($startMonth, $startYear) {
                return $item->month == $startMonth && $item->year == $startYear;
            });

            $formattedTransaksi->push([
                'month' => $month,
                'net_profit' => $transaksiData ? $transaksiData->net_profit : '0',
            ]);

            $startMonth += 1;
            if ($startMonth > 12) {
                $startMonth = 1;
                $startYear += 1;
            }
        }

        return response()->json($formattedTransaksi);
    }
}
