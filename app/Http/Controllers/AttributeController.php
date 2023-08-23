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
        // Tambahkan middleware autentikasi ke metode 'store'
        $this->middleware('auth')->only('store');
    }

    public function index()
    {
        // ID from active year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Get Data Attribute
        $attributes = Attribute::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        // Get Data Category
        $categories = Category::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        // Get Data Credit
        $credits = Credit::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        // Get Data Category - Attribute Relation
        $categoriesRelation = Category::has("attributes")->where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        // Get Data Notification
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        // Parsing data to view
        return view('setting.attribute.index', compact('credits', 'attributes', 'categories', 'notifications', 'categoriesRelation'));
    }

    // Attribute Control Start
    public function store(Request $request)
    {
        // ID from active year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Create data attribute
        $validator = Validator::make($request->all(), [
            'attribute_name' => 'required|unique:attributes,attribute_name,null,id,year_id,' . $activeYearId,
            'attribute_price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $attributes = Attribute::create([
            'attribute_name' => $request->input('attribute_name'),
            'attribute_price' => $request->input('attribute_price'),
            'year_id' => $activeYearId,
            'user_id' => Auth::user()->id
        ]);

        // Create data notification
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
        ], 201); // 201 updated
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
    // Attribute Control End

    public function add()
    {
        // Dapatkan ID tahun yang aktif
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $categories = Category::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $attributes = Attribute::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

        $credits = Credit::where('year_id', $activeYearId)->orderBy("updated_at", "DESC")->get();

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

            // Menghapus semua relasi atribut dari kategori
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
