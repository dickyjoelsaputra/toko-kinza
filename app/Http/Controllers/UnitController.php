<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::get();
        return view('unit.index', ['units' => $units]);
    }

    public function show($id)
    {
        $unit = Unit::find($id);
        return response()->json($unit);
    }

    public function ajaxCreate(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'alias' => 'required',
        ]);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to create',
            ]);
        }

        $data['name'] = strtoupper($data['name']);
        $data['alias'] = strtoupper($data['alias']);

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

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if ($unit) {
            $unit->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Unit deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to delete',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'alias' => 'required',
        ]);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to update',
            ]);
        }

        $data['name'] = strtoupper($data['name']);
        $data['alias'] = strtoupper($data['alias']);

        $unit = Unit::find($id);
        $result = $unit->update($data);
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Unit updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unit failed to update',
            ]);
        }
    }
}
