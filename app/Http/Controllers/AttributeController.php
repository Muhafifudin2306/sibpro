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
        $validator = Validator::make($request->all(), [
            'attribute_name' => 'required|unique:attributes,attribute_name,null',
            'attribute_price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $attributes = Attribute::create([
            'attribute_name' => $request->input('attribute_name'),
            'attribute_price' => $request->input('attribute_price'),
            'user_id' => Auth::user()->id
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Atribut" . " " . $request->input('attribute_name') . " " . "dengan harga" . " " . "Rp" . $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
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

        $attributes->update([
            "attribute_name" =>  $request->input('attribute_name'),
            "attribute_price" =>  $request->input('attribute_price'),
            'user_id' => Auth::user()->id
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Atribut" . " " . $attributes->attribute_name . " " . "dengan harga" . " " . "Rp" .  $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
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
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Atribut" . " " . $attribute->attribute_name . " " . "dengan harga" . " " . "Rp" . $attribute->attribute_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }

    public function add()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $categories = Category::orderBy("updated_at", "DESC")->get();

        $attributes = Attribute::orderBy("updated_at", "DESC")->get();

        $credits = Credit::orderBy("updated_at", "DESC")->get();

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('setting.attribute.add', compact('credits', 'notifications', 'categories', 'attributes'));
    }

    public function storeRelation(Request $request)
    {
        $category = Category::find($request->input('category_id'));
        $attributeIds = $request->input('attribute_id');
        $creditIds = $request->input('credit_id');

        $category->attributes()->sync($attributeIds);
        $category->credits()->sync($creditIds);

        $activeYearId = Year::where('year_status', 'active')->value('id');

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Relasi Data Kategori" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return redirect()->route('attribute')->with('success', 'Relasi Kategori-Atribut berhasil dibuat.');
    }


    public function destroyRelation($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->attributes()->detach();
            $category->credits()->detach();

            $activeYearId = Year::where('year_status', 'active')->value('id');
            $years = Year::find($activeYearId);

            Notification::create([
                'notification_content' => Auth::user()->name . " " . "Menghapus Relasi Data Kategori" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
                'notification_status' => 0
            ]);
            return redirect()->route('attribute')->with('success', 'Relasi Kategori-Atribut berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
