<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    //Menambahkan data ke database
    public function store(Request $request)
    {

        //Memvalidasi inputan
        $validator = validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);


        // Kondisi apabila inputan yang digunakan tidak sesuai
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }


        $validated = $validator->validated();
        //masukkan inputan yang benar ke dalam database (table category)
        Category::create([
            'name' => $validated['name'],
        ]);


        //response json akan dikirim jika inputan benar
        return response()->json([
            'msg' => 'Data produk berhasil disimpan'
        ], 201);
    }



    //Show data
    function showAll()
    {
        $category = Category::all();
        return response()->json([
            'msg' => 'Data Produk Keseluruhan',
            'data' => $category
        ], 200);
    }



    //Update Data
    public function update(Request $request, $id)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);


        // Kondisi apabila inputan yang digunakan tidak sesuai
        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();
        Category::where('id', $id)->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'msg' => 'Dta produk berhasil diubah'
        ], 201);
    }



    //Delete Data
    public function delete($id)
    {
        $category = Category::where('id', $id)->get();

        if ($category) {
            Category::where('id', $id)->delete();

            return response()->json([
                'msg' => 'Data produk dengan ID: ' . $id . ' berhasil dihapus'
            ], 201);
        }
        return response()->json([
            'msg' => 'Data produk dengan ID:' . $id . 'tidak ditemukan'
        ], 404);
    }



    //Show By ID
    public function showById($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                "msg" => 'Data produk dengan ID: ' . $id,
                'data' => $category
            ], 200);
        }
        return response()->json([
            'msg' => 'Data produk dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }



    //Show By Name
    public function showByName($name)
    {

        $category = Category::find($name);

        //cari data berdasarkan nama produk yang mirip
        $category = Category::where('name', 'LIKE', '%' . $name . '%')->get();

        //apabila data produk ada
        if ($category->count() > 0) {
            return response()->json([
                'msg' => "Data Produk dengan nama yang mirip: " . $name,
                'data' => $category
            ], 200);
        }

        //response ketika data tidak ada
        return response()->json([
            'msg' => 'Data produk dengan nama yang mirip: ' . $name . ' tidak ditemukan',
        ], 404);
    }
}