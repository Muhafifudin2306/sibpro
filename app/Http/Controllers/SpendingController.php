<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexAttribute()
    {
        $attributes = Attribute::select('attribute_name','slug')->where('attribute_type',1)->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.attribute.index', compact('attributes','notifications'));
    }

    public function detailAttribute($slug)
    {
        $data = Attribute::where('slug',$slug)->first();

        if (!$data) {
            abort(404);
        }

        $id = $data->id;

        $attribute = Attribute::find($id);
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $sumDebit = Payment::where('attribute_id', $id)->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})->where('status', 'Paid')->sum('price');
        $sumSpending = Spending::where('attribute_id', $id)->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})->where('spending_type',1)->sum('spending_price');
        $spendings = Spending::select('id','spending_desc','spending_price', 'spending_type','spending_date')
                                    ->whereHas('year', function ($query) {$query->where('id', '=', Year::where('year_current', 'selected')->value('id'));})
                                    ->orderBy("updated_at", "DESC")
                                    ->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        return view('spending.attribute.detail', compact('years','attribute','notifications','sumDebit','sumSpending','spendings'));
    }
    public function storeSpending(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        $spendingDate = $request->input('spending_date');
        $spendingDesc = $request->input('spending_desc');
        $spendingType = $request->input('spending_type');
        $spendingPrice = $request->input('spending_price');
        $attributeId = $request->input('attribute_id');


        $spendings = Spending::create([
            'spending_date' => $spendingDate,
            'spending_desc' => $spendingDesc,
            'spending_type' => $spendingType,
            'spending_price' => $spendingPrice,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId
        ]);


        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data credit" . " " . $spendingDesc . " " . "dengan harga" . " " . "Rp" .  $spendingPrice . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $spendings,
        ], 201);
    }

    public function destroySpending($id)
    {
        $spending = Spending::find($id);

        if (!$spending) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $spending->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data kredit" . " " . $spending->spending_name . " " . "dengan harga" . " " . "Rp" . $spending->spending_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kredit berhasil dihapus.']);
    }

    public function updateSpending(Request $request, $id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        $spendingDate = $request->input('spending_date');
        $spendingDesc = $request->input('spending_desc');
        $spendingType = $request->input('spending_type');
        $spendingPrice = $request->input('spending_price');
        $attributeId = $request->input('attribute_id');


        $spending = Spending::find($id);
        
        $spending->update([
            'spending_date' => $spendingDate,
            'spending_desc' => $spendingDesc,
            'spending_type' => $spendingType,
            'spending_price' => $spendingPrice,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId
        ]);


        Notification::create([
            'notification_content' => Auth::user()->name . " " . "memperbarui data kredit" . " " . $spending->spending_desc . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $spending,
        ], 201);
    }
}
