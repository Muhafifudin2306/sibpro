<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Vendor;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::select('id','vendor_name','updated_at')->orderBy('updated_at', 'DESC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        return view('master.vendor.index',compact('students', 'vendors','notifications'));
    }

    public function storeVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        Vendor::create([
            'vendor_name' => $request->input('vendor_name')
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menambah data vendor" . " " . $request->input('point_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data inserted successfully'], 201);
    }

     public function destroyVendor($id)
     {
         $vendor = Vendor::find($id);
 
         if (!$vendor) {
             return response()->json(['message' => 'Data POS tidak ditemukan.'], 404);
         }
 
         $vendor->delete();
         $activeYearId = Year::where('year_status', 'active')->value('id');
         $years = Year::find($activeYearId);
 
         Notification::create([
             'notification_content' => Auth::user()->name . " " . "menghapus data vendor" . " " . $vendor->vendor_name . " " . "pada tahun ajaran" . " " . $years->year_name,
             'notification_status' => 0
         ]);
 
         return response()->json(['message' => 'Data kategori berhasil dihapus.']);
     }
     public function updateVendor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $vendor->update([
            'vendor_name' => $request->input('vendor_name')
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "memperbarui data vendor" . " " . $request->input('vendor_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data updated successfully'], 200);
    }
}
