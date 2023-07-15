<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categorys = Category::get();
        return view('category.index', ['categorys' => $categorys]);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }

    public function ajaxCreate(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
        ]);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category failed to create',
            ]);
        }

        $data['name'] = strtoupper($data['name']);

        $result = Category::create($data);
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category failed to create',
            ]);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category failed to delete',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category failed to update',
            ]);
        }

        $data['name'] = strtoupper($data['name']);

        $category = Category::find($id);
        $result = $category->update($data);
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category failed to update',
            ]);
        }
    }
}
