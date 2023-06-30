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
        $manualOnly = $request->input('manual');


        $items = Item::with('prices.unit');

        // if ($manualOnly == "true") {
        //     $items->where('manual', true);
        // }

        // $items->when($search, function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%')
        //         ->orWhere('code', 'like', '%' . $search . '%');
        // });
        if ($manualOnly == "true") {
            $items->where(function ($query) use ($search) {
                $query->where('manual', true)
                    ->where(function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    });
            });
        }

        $items->when(!$manualOnly || $manualOnly == "false", function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        });

        $items = $items->paginate(10);

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
