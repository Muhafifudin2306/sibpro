<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Tambahkan middleware autentikasi ke metode 'store'
        $this->middleware('auth')->only('store');
    }

    public function store(Request $request)
    {
        // Dapatkan ID tahun aktif dari tabel Year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Simpan data atribut untuk tahun aktif
        $categories = Category::create([
            'category_name' => $request->input('category_name'),
            'user_id' => Auth::user()->id
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data kategori" . " " . $request->input('category_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $categories,
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $category->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Kategori" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $category->update([
            "category_name" =>  $request->input('category_name'),
            'user_id' => Auth::user()->id
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Kategori" . " " . $category->category_name  . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $category,
        ], 201); // 201 updated
    }
}
