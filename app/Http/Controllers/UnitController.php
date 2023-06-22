<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return view('unit.index');
    }
    public function ajaxCreate(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'alias' => 'required',
            'quantity' => 'required',
        ]);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to create',
            ]);
        }

        $data['name'] = ucwords($data['name']);
        $data['alias'] = ucwords($data['alias']);

        $result = Unit::create($data);
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unit created successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to create',
            ]);
        }
    }
}
