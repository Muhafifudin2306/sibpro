<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\Year;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $attributes = Attribute::orderBy("updated_at", "DESC")->get();
        $categories = Category::orderBy("updated_at", "DESC")->get();
        $credits = Credit::orderBy("updated_at", "DESC")->get();
        $categoriesRelation = Category::has("attributes")->orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        return view('setting.attribute.index', compact('credits', 'attributes', 'categories', 'notifications', 'categoriesRelation'));
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $attributeName = $request->input('attribute_name');

        $slug = Str::slug($attributeName);

        $attributes = Attribute::create([
            'attribute_name' => $attributeName,
            'attribute_price' => $request->input('attribute_price'),
            'attribute_type' => $request->input('attribute_type'),
            'vendor_id' => $request->input('vendor_id'),
            'slug' => $slug
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data atribut Daftar Ulang" . " " . $request->input('attribute_name') . " " . "dengan harga" . " " . "Rp" . $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $attributes,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $attributes = Attribute::find($id);
        $attributeName = $request->input('attribute_name');

        $slug = Str::slug($attributeName);
        $attributes->update([
            "attribute_name" => $attributeName,
            "attribute_price" =>  $request->input('attribute_price'),
            "attribute_type" => $request->input('attribute_type'),
            "vendor_id" => $request->input('vendor_id'),
            'slug' => $slug
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data atribut Daftar Ulang" . " " . $attributes->attribute_name . " " . "dengan harga" . " " . "Rp" .  $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $attributes,
        ], 201);
    }

    public function destroy($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $attribute->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data atribut Daftar Ulang" . " " . $attribute->attribute_name . " " . "dengan harga" . " " . "Rp" . $attribute->attribute_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
}
