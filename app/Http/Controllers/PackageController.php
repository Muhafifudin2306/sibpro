<?php

namespace App\Http\Controllers;

use App\Exports\CategoriesExport;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Year;
use Maatwebsite\Excel\Facades\Excel;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $attributes = Attribute::select('id','attribute_name','attribute_price', 'attribute_type','vendor_id','updated_at','slug')->orderBy("updated_at", "DESC")->get();
        $categories = Category::select('id','category_name','updated_at','slug')->orderBy("updated_at", "DESC")->get();
        $credits = Credit::select('id','credit_type','credit_name','credit_price','semester','updated_at','slug')->orderBy("updated_at", "DESC")->get();
        $categoriesRelation = Category::has("attributes")->orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $vendors = Vendor::select('id','vendor_name')->orderBy('updated_at','DESC')->get();
        return view('setting.attribute.index', compact('students','credits', 'attributes', 'categories','vendors', 'notifications', 'categoriesRelation'));
    }


    public function add()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $categories = Category::orderBy("updated_at", "DESC")->get();
        $attributes = Attribute::orderBy("updated_at", "DESC")->get();
        $credits = Credit::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();
        return view('setting.attribute.add', compact('students', 'credits', 'notifications', 'categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $category = Category::find($request->input('category_id'));

        $attributeIds = $request->input('attribute_id');
        $creditIds = $request->input('credit_id');

        $category->attributes()->sync($attributeIds);
        $category->credits()->sync($creditIds);

        $activeYearId = Year::where('year_status', 'active')->value('id');

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data paket" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data paket berhasil dibuat!'
        ], 201);
    }

    public function edit($id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $students = StudentClass::orderBy("class_name", 'ASC')->get();

        $category = Category::find($id);

        $categories = Category::where('id', '!=', $id)->orderBy("updated_at", "DESC")->get();


        $attributes = $category->attributes;
        $allAttribute = Attribute::all();

        $credits = $category->credits;
        $allCredit = Credit::all();

        $notifications = Notification::orderBy("updated_at", 'DESC')->limit(10)->get();

        return view('setting.attribute.edit', compact('students', 'category', 'credits', 'notifications', 'categories', 'attributes', 'allAttribute', 'allCredit'));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->attributes()->detach();
        $category->credits()->detach();

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus paket" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json(['message' => 'Data paket berhasil dihapus.']);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $attributeIds = $request->input('attribute_id');
        $category->attributes()->sync($attributeIds);
        $creditIds = $request->input('credit_id');
        $category->credits()->sync($creditIds);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data paket" . " " . $category->category_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data paket berhasi diedit!',
            'data' => $category,
        ], 200);
    }

    public function downloadExcel()
    {
        // Menggunakan Ekspor Excel dengan dua tab
        return Excel::download(new CategoriesExport(), 'categories.xlsx');
    }
}
