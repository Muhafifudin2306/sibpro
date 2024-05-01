<?php

namespace App\Http\Controllers;

use App\Models\ExternalSpending;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExternalSpendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexOperasional()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $externals = ExternalSpending::where('is_operational', 1)->where('year_id', $activeYearId)->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.operasional.index', compact('students','notifications', 'years', 'externals'));
    }

    public function indexNonOperasional()
    {
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.non-operasional.index', compact('students','notifications'));
    }

    public function storeOperasional(Request $request)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required',
            'spending_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }
        ExternalSpending::create([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),           
            'spending_date' => $request->input('spending_date'),
            'spending_type' => $request->input('spending_type'),
            'is_operational' => 1,
            'year_id' => $activeYearId
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data pengeluaran operasional dengan deskripsi" . " " . $request->spending_desc . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);


        return response()->json(['message' => 'Data inserted successfully'], 201);
    }

    public function destroy($id)
    {
        $external = ExternalSpending::find($id);

        if (!$external) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $external->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function updateOperasional(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required',
            'spending_type' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }
        $activeYearId = Year::where('year_current', 'selected')->value('id');
    
        $externalSpending = ExternalSpending::findOrFail($id);
    
        $externalSpending->update([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),           
            'spending_date' => $request->input('spending_date'),
            'spending_type' => $request->input('spending_type')
        ]);

        $years = Year::find($activeYearId);
    
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data data income eksternal dengan deskripsi" . " " . $request->input('income_desc') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
    
        return response()->json(['message' => 'Data updated successfully'], 200);
    }
}
