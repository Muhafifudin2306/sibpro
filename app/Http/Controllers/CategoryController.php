<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $activeYearId = Year::where('year_status', 'active')->value('id');

        $categoryName = $request->input('category_name');
        $slug = Str::slug($categoryName);

        $category = Category::create([
            'category_name' => $categoryName,
            'slug' => $slug,
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data kategori" . " " . $categoryName . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $category,
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Data kategori tidak ditemukan.'], 404);
        }

        $category->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data kategori" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kategori berhasil dihapus.']);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $newCategoryName = $request->input('category_name');
        
        $newSlug = Str::slug($newCategoryName);

        $category->update([
            "category_name" => $newCategoryName,
            "slug" => $newSlug,
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data kategori" . " " . $category->category_name  . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $category,
        ], 201);
    }
}
