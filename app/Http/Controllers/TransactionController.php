<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GrahamCampbell\ResultType\Success;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');

        $transaction = Transaction::with(['user', 'carts'])
            ->when($date, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->orderByDesc('created_at')->paginate(20);

        return view('transaction.index', ['transaction' => $transaction, 'date' => $date]);
    }

    public function ajaxStore(Request $request)
    {

        $netProfit = 0;

        foreach ($request->allCarts as $val) {
            $cap = Item::find($val['itemId'])->capital;
            $netProfit += intval(str_replace(".", "", $val['subtotal'])) - $cap * $val['quantity'];
        }

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'total' => intval(str_replace(".", "", $request->total,)),
            'pay' => $request->costumerMoney,
            'net_profit' => $netProfit,
            'change' => intval(str_replace(".", "", $request->changeMoney,)),
        ]);

        foreach ($request->allCarts as $value) {
            $item = Item::find($value['itemId']);

            $transaction->carts()->create([
                'item_name' => "[ " . $item->category->name . " ] " . $item->name,
                'quantity' => $value['quantity'],
                'capital' => $item->capital,
                'price' => intval(str_replace(".", "", $value['price'])),
                'subtotal' => intval(str_replace(".", "", $value['subtotal'])),
            ]);
        }

        return response()->json([
            'success' => 'success',
            'message' => 'Data berhasil disimpan',
        ]);

    }
}
