<?php

namespace App\Http\Controllers;

use App\Models\ExternalIncome;
use App\Models\Notification;
use App\Models\PointOfSales;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExternalIncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $externals = ExternalIncome::select('id','pos_id','income_desc','income_period','income_price','updated_at')->orderBy('updated_at','DESC')->get();
        $pos = PointOfSales::select('id','point_code','point_source','point_name')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('external.index', compact('externals','notifications','pos'));
    }

    public function storeExternal(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $validator = Validator::make($request->all(), [
            'pos_id' => 'required|exists:point_of_sales,id',
            'income_price' => 'required|numeric',
            'income_desc' => 'required|max:255',
            'income_period' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }
        $incomePeriod = $request->input('income_period') . '-01';

        ExternalIncome::create([
            'pos_id' => $request->input('pos_id'),
            'income_price' => $request->input('income_price'),
            'income_desc' => $request->input('income_desc'),
            'income_period' => $incomePeriod,
            'year_id' => $activeYearId
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data income eksternal dengan deskripsi" . " " . $request->income_desc . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);


        return response()->json(['message' => 'Data inserted successfully'], 201);
    }

    public function destroyExternal($id)
    {
        $external = ExternalIncome::find($id);

        if (!$external) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $external->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data data income eksternal dengan deskripsi" . " " . $external->income_desc . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kategori berhasil dihapus.']);
    }

    public function updateExternal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pos_id' => 'required',
            'income_desc' => 'required|max:255',
            'income_period' => 'required',
            'income_price' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }
        $activeYearId = Year::where('year_status', 'active')->value('id');
    
        $externalIncome = ExternalIncome::findOrFail($id);

        $incomePeriod = $request->input('income_period') . '-01';
    
        $externalIncome->update([
            'pos_id' => $request->input('pos_id'),
            'income_desc' => $request->input('income_desc'),
            'income_period' => $incomePeriod,
            'income_price' => $request->input('income_price'),
            'year_id' => $activeYearId
        ]);

        $years = Year::find($activeYearId);
    
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data data income eksternal dengan deskripsi" . " " . $request->input('income_desc') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
    
        return response()->json(['message' => 'Data updated successfully'], 200);
    }
}
