<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $category = Category::create($validated);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            'data' => $category
        ], 201);
    }

    public function showAll()
    {
        $categories = Category::all();
        return response()->json([
            'msg' => 'Data Kategori Keseluruhan',
            'data' => $categories
        ], 200);
    }

    public function showById($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'msg' => 'Data Kategori Dengan ID: ' . $id,
                'data' => $category
            ], 200);
        }
        return response()->json([
            'msg' => 'Data Kategori dengan ID: ' . $id . ' Tidak Ditemukan'
        ], 404);
    }

    public function showByName($name)
    {
        $categories = Category::where('name', 'LIKE', '%' . $name . '%')->get();
        if ($categories->count() > 0) {
            return response()->json([
                'msg' => 'Data Produk Dengan Nama Yang Mirip: ' . $name,
                'data' => $categories
            ], 200);
        }
        return response()->json([
            'msg' => 'Data Produk dengan Nama Yang Mirip: ' . $name . ' Tidak Ditemukan'
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $category = Category::find($id);

        if ($category) {
            $category->update($validated);

            return response()->json([
                'msg' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                'data' => $category
            ], 200);
        }

        return response()->json([
            'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            return response()->json([
                'msg' => 'Data kategori dengan ID: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'msg' => 'Data kategori dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
