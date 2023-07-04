<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        $items->orderByDesc('id');

        $items = $items->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Item',
            'data' => $items
        ]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        if ($item->is_photo) {
            if (Storage::exists($item->photo)) {
                Storage::delete($item->photo);
            }
        };

        if ($item) {
            $item->delete();
            $item->prices()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Item failed to delete',
            ]);
        }
    }


    public function create()
    {
        $units = Unit::get();
        return view('item.create', ['units' => $units]);
    }

    public function createAjax(Request $request)
    {
        $rules = [
            'code' => 'nullable',
            'name' => 'required',
            'capital' => 'required',
            'items.*.price' => 'required',
            'items.*.minimal' => 'required',
            'items.*.unit' => 'required',
            'image' => 'nullable',

        ];

        $messages = [
            'name.required' => 'Nama diperlukan.',
            'name.required' => 'Harga Modal diperlukan.',
            'items.*.price.required' => 'Harga pada barang diperlukan.',
            'items.*.minimal.required' => 'Jumlah minimal pada barang diperlukan.',
            'items.*.unit.required' => 'Satuan pada barang diperlukan.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();

            return response()->json([
                'status' => 'error',
                'message' => $errorMessages,
            ], 400);
        }

        $validasiCode = Item::where('code', $request->code)->first();
        if ($validasiCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode sudah digunakan',
            ], 400);
        }

        $request->manual = false;
        if ($request->code == null) {
            $request->code = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $request->manual = true;
        }

        $request->nama = strtoupper($request->nama);

        if ($request->image != null) {
            $base64Image = $request->image;
            $imageName = Str::random(40) . '.png';
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            $imagePath = 'barang/' . $imageName;
            $image = Image::make($imageData);

            $image->resize(150, 150);

            $compressedImageData = $image->encode('png', 50);

            Storage::disk('public')->put($imagePath, $compressedImageData);

            $request->image = $imagePath;
            $request->is_photo = true;
        } else {
            $request->image = 'default/default.png';
            $request->is_photo = false;
        }

        $item = Item::create([
            'code' => $request->code,
            'name' => strtoupper($request->name),
            'capital' => intval(str_replace(".", "", $request->capital),),
            'photo' => $request->image,
            'manual' => $request->manual,
            'is_photo' => $request->is_photo,
        ]);

        foreach ($request->items as $itemData) {
            $item->prices()->create([
                'price' => intval(str_replace(".", "", $itemData['price'],)),
                'minimal' => $itemData['minimal'],
                'unit_id' => $itemData['unit'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Item created successfully',
        ]);
    }

    public function createPhone()
    {
        return view('item.create-phone');
    }
}
