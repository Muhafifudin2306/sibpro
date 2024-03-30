<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PointOfSales;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PointOfSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $poses = PointOfSales::select('id','point_name','point_source','point_code','updated_at')->orderBy('updated_at', 'DESC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        return view('master.pos.index',compact('students', 'poses','notifications'));
    }

    public function storePos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'point_name' => 'required|max:255',
            'point_code' => 'required|max:20|unique:point_of_sales,point_code',
            'point_source' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        PointOfSales::create([
            'point_name' => $request->input('point_name'),
            'point_code' => $request->input('point_code'),
            'point_source' => $request->input('point_source'),
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menambah data pos" . " " . $request->input('point_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data inserted successfully'], 201);
    }

    public function destroyPos($id)
    {
        $pos = PointOfSales::find($id);

        if (!$pos) {
            return response()->json(['message' => 'Data POS tidak ditemukan.'], 404);
        }

        $pos->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data kategori" . " " . $pos->point_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kategori berhasil dihapus.']);
    }

    public function updatePos(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'point_name' => 'required|max:255',
            'point_code' => 'required|max:20|unique:point_of_sales,point_code,' . $id,
            'point_source' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $point = PointOfSales::find($id);

        if (!$point) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $point->update([
            'point_name' => $request->input('point_name'),
            'point_code' => $request->input('point_code'),
            'point_source' => $request->input('point_source'),
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "memperbarui data pos" . " " . $request->input('point_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data updated successfully'], 200);
    }
}
