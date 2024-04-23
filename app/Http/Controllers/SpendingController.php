<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Debt;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\StudentClass;
use App\Models\Vendor;
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
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $attributes = Attribute::select('attribute_name','slug')->where('attribute_type',1)->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.attribute.index', compact('students','attributes','notifications'));
    }

    public function detailAttribute($slug)
    {
        $data = Attribute::where('slug',$slug)->first();

        if (!$data) {
            abort(404);
        }

        $id = $data->id;

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        
        $attribute = Attribute::find($id);
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $sumDebit = Payment::where('attribute_id', $id)->where('year_id', $activeYearId)->where('status', 'Paid')->sum('price');
        $sumSpending = Spending::where('attribute_id', $id)->where('year_id', $activeYearId)->sum('spending_price');
        $spendings = Spending::select('id','spending_desc','spending_price', 'spending_type','spending_date','vendor_id')
                                    ->where('year_id', $activeYearId)
                                    ->orderBy("updated_at", "DESC")
                                    ->where('attribute_id',$id)
                                    ->get();
        $sumDebt = Debt::where('is_paid',0)->where('year_id', $activeYearId)->where('attribute_id', $id)->sum('debt_amount');

        $debts = Debt::select('id','description','debt_amount','vendor_id','due_date','is_paid')->where('year_id', $activeYearId)
                                    ->orderBy("updated_at", "DESC")
                                    ->where('attribute_id',$id)
                                    ->get();

        $vendors = Vendor::select('id','vendor_name')->orderBy("updated_at", "DESC")->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        return view('spending.attribute.detail', compact('students','debts','sumDebt','vendors','years','attribute','notifications','sumDebit','sumSpending','spendings'));
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
        $vendorId = $request->input('vendor_id');

        $spendings = Spending::create([
            'spending_date' => $spendingDate,
            'spending_desc' => $spendingDesc,
            'spending_type' => $spendingType,
            'spending_price' => $spendingPrice,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId
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
        $vendorId = $request->input('vendor_id');


        $spending = Spending::find($id);
        
        $spending->update([
            'spending_date' => $spendingDate,
            'spending_desc' => $spendingDesc,
            'spending_type' => $spendingType,
            'spending_price' => $spendingPrice,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId
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

    public function storeDebt(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        $dueDate = $request->input('due_date');
        $description = $request->input('description');
        $isPaid = $request->input('is_paid');
        $debtAmount = $request->input('debt_amount');
        $attributeId = $request->input('attribute_id');
        $vendorId = $request->input('vendor_id');

        $debts = Debt::create([
            'due_date' => $dueDate,
            'description' => $description,
            'is_paid' => $isPaid,
            'debt_amount' => $debtAmount,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId
        ]);


        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data hutang" . " " . $description . " " . "dengan harga" . " " . "Rp" .  $debtAmount . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $debts,
        ], 201);
    }

    public function destroyDebt($id)
    {
        $debt = Debt::find($id);

        if (!$debt) {
            return response()->json(['message' => 'Data hutang tidak ditemukan.'], 404);
        }

        $debt->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data hutang" . " " . $debt->description . " " . "dengan harga" . " " . "Rp" . $debt->debt_amount . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data hutang berhasil dihapus.']);
    }

    public function updateDebt(Request $request,$id)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        $dueDate = $request->input('due_date');
        $description = $request->input('description');
        $isPaid = $request->input('is_paid');
        $debtAmount = $request->input('debt_amount');
        $attributeId = $request->input('attribute_id');
        $vendorId = $request->input('vendor_id');

        $debts = Debt::find($id);
        
        $debts->update([
            'due_date' => $dueDate,
            'description' => $description,
            'is_paid' => $isPaid,
            'debt_amount' => $debtAmount,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId
        ]);


        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data hutang" . " " . $description . " " . "dengan harga" . " " . "Rp" .  $debtAmount . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $debts,
        ], 201);
    }
}
