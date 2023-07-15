<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        return view('cashier.index');
    }

    public function cashierScan(Request $request)
    {
        $scan = $request->input('scan');
        $dataArray = $request->input('dataarray') ?? [];

        $item = Item::whereNotIn('id', $dataArray)
            ->where(function ($query) use ($scan) {
                $query->where('name', 'like', "$scan%")
                    ->orWhere('code', 'like', "$scan%");
            })->with('prices.unit', 'category')
            ->first();

        if (!$item) {
            // error response
            return response()->json([
                'status' => 'error',
                'message' => 'Barang sudah digunakan / Tidak ditemukan',
            ], 400);
        }

        return response()->json(['results' => $item, 'results2' => $dataArray]);
    }

    public function search(Request $request)
    {
        $searchInput = $request->input('searchinput');
        $dataArray = $request->input('dataarray') ?? [];

        $results = Item::whereNotIn('id', $dataArray)
            // ->where('name', 'like', "%$searchInput%")
            // ->orWhere('code', 'like', "%$searchInput%")
            ->where(function ($query) use ($searchInput) {
                $query->where('name', 'like', "%$searchInput%")
                    ->orWhere('code', 'like', "%$searchInput%")
                    ->orWhereHas('category', function ($query) use ($searchInput) {
                        $query->where('name', 'like', "%$searchInput%");
                    });
            })
            // ->where(function ($query) use ($searchInput) {
            //     $query->where('name', 'like', "%$searchInput%")
            //         ->orWhereHas('category', function ($query) use ($searchInput) {
            //             $query->where('name', 'like', "%$searchInput%");
            //         });
            // })
            // ->where(function ($query) use ($searchInput) {
            //     $query->where(function ($query) use ($searchInput) {
            //         $query->where('name', 'like', "%$searchInput%")
            //             ->orWhere('code', 'like', "%$searchInput%");
            //     })
            //         ->orWhereHas('category', function ($query) use ($searchInput) {
            //             $query->where('name', 'like', "%$searchInput%");
            //         });
            // })
            ->with('category')
            ->get();

        return response()->json(['results' => $results]);
    }

    public function getItem(Request $request)
    {
        $itemId = $request->input('itemId');
        $item = Item::with('prices.unit', 'category')->find($itemId);
        return response()->json(['results' => $item]);
    }
}
