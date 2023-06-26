<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('item.index');
    }
    public function ajaxIndex(Request $request)
    {
        $search = $request->input('search');
        $items = Item::with('prices.unit')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Item',
            'data' => $items
        ]);
    }


    public function createComp()
    {
        return view('item.create-comp');
    }

    public function createPhone()
    {
        return view('item.create-phone');
    }
}
