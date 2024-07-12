<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Year;
use App\Models\Vendor;
use App\Models\Payment;
use App\Models\Spending;
use App\Models\Attribute;
use App\Models\Notification;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SpendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexAttribute()
    {
        
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $attribute = Attribute::latest()->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $totalCreditDUs = Payment::where('type', 'Daftar Ulang')
                    ->where('year_id', $activeYearId)
                    ->where('status', 'Paid')
                    ->whereHas('attribute', function ($query) {
                        $query->where('attribute_type', 'Tabungan');
                    })
                    ->get();

        $tabunganDaftarUlang = $totalCreditDUs->reduce(function ($carry, $transaction) {
            return $carry + 50000;
        }, 0);
        $paidDebit = Payment::where('year_id', $activeYearId)->where('status', 'Paid')->sum('price');
        $sumDebit = $paidDebit - $tabunganDaftarUlang;
        $sumSpending = Spending::where('year_id', $activeYearId)->sum('spending_price');
        $spendings = Spending::select('id', 'attribute_id', 'spending_desc', 'spending_price', 'spending_type', 'spending_date', 'vendor_id', 'image_url')
            ->where('year_id', $activeYearId)
            ->orderBy("updated_at", "DESC")
            ->get();
        $sumDebt = Debt::where('is_paid', 0)->where('year_id', $activeYearId)->sum('debt_amount');

        $debts = Debt::select('id', 'description', 'debt_amount', 'vendor_id', 'due_date', 'is_paid', 'image_url','attribute_id')->where('year_id', $activeYearId)
            ->orderBy("updated_at", "DESC")
            ->get();

        $vendors = Vendor::select('id', 'vendor_name')->orderBy("updated_at", "DESC")->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        return view('spending.attribute.index', compact('students', 'debts', 'sumDebt', 'vendors', 'years', 'attribute', 'notifications', 'sumDebit', 'sumSpending', 'spendings'));
    }

    public function detailAttribute($slug)
    {
        $data = Attribute::where('slug', $slug)->first();

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
        $spendings = Spending::select('id', 'spending_desc', 'spending_price', 'spending_type', 'spending_date', 'vendor_id', 'image_url')
            ->where('year_id', $activeYearId)
            ->orderBy("updated_at", "DESC")
            ->where('attribute_id', $id)
            ->get();
        $sumDebt = Debt::where('is_paid', 0)->where('year_id', $activeYearId)->where('attribute_id', $id)->sum('debt_amount');

        $debts = Debt::select('id', 'description', 'debt_amount', 'vendor_id', 'due_date', 'is_paid', 'image_url')->where('year_id', $activeYearId)
            ->orderBy("updated_at", "DESC")
            ->where('attribute_id', $id)
            ->get();

        $vendors = Vendor::select('id', 'vendor_name')->orderBy("updated_at", "DESC")->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        return view('spending.attribute.detail', compact('students', 'debts', 'sumDebt', 'vendors', 'years', 'attribute', 'notifications', 'sumDebit', 'sumSpending', 'spendings'));
    }

    public function storeSpending(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'spending_date' => 'required|date',
            'spending_desc' => 'required|string|max:255',
            'spending_type' => 'required|string|max:255',
            'spending_price' => 'required|numeric',
            'attribute_id' => 'required|exists:attributes,id',
            'vendor_id' => 'required|exists:vendors,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imagePath = $image->storeAs('public/petugas', $image->hashName());
            $imageUrl = Storage::url($imagePath);
        } else {
            $imageUrl = null;
        }

        $spending = Spending::create([
            'spending_date' => $request->input('spending_date'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_type' => $request->input('spending_type'),
            'spending_price' => $request->input('spending_price'),
            'year_id' => $activeYearId,
            'attribute_id' => $request->input('attribute_id'),
            'vendor_id' => $request->input('vendor_id'),
            'image_url' => $imageUrl
        ]);

        Notification::create([
            'notification_content' => Auth::user()->name . " membuat data kredit " . $request->input('spending_desc') . " dengan harga Rp " .  $request->input('spending_price') . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $spending,
        ], 201);
    }

    public function destroySpending($id)
    {
        $spending = Spending::find($id);

        if (!$spending) {
            return response()->json(['message' => 'Data kredit tidak ditemukan.'], 404);
        }

        if ($spending->image_url) {
            $imagePath = str_replace('/storage', 'public', $spending->image_url);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $spending->delete();

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " menghapus data kredit " . $spending->spending_desc . " dengan harga Rp " . $spending->spending_price . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data kredit berhasil dihapus.']);
    }

    public function updateSpending(Request $request, $id)
    {
        $validatedData = $request->validate([
            'spending_date' => 'required|date',
            'spending_desc' => 'required|string|max:255',
            'spending_type' => 'required|string|max:255',
            'spending_price' => 'required|numeric',
            'attribute_id' => 'required|exists:attributes,id',
            'vendor_id' => 'required|exists:vendors,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $spending = Spending::find($id);
        if (!$spending) {
            return response()->json(['message' => 'Spending not found'], 404);
        }

        $imagePath = $spending->image_url;
        if ($request->hasFile('image_url')) {
            if ($spending->image_url) {
                Storage::delete(str_replace('/storage', 'public', $spending->image_url));
            }

            $file = $request->file('image_url');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/petugas', $filename);
            $imagePath = Storage::url($path);
        }

        $spending->update([
            'spending_date' => $validatedData['spending_date'],
            'spending_desc' => $validatedData['spending_desc'],
            'spending_type' => $validatedData['spending_type'],
            'spending_price' => $validatedData['spending_price'],
            'year_id' => $activeYearId,
            'attribute_id' => $validatedData['attribute_id'],
            'vendor_id' => $validatedData['vendor_id'],
            'image_url' => $imagePath
        ]);

        Notification::create([
            'notification_content' => Auth::user()->name . " memperbarui data kredit " . $spending->spending_desc . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $spending,
        ], 200);
    }


    public function storeDebt(Request $request)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $dueDate = $request->input('due_date');
        $description = $request->input('description');
        $isPaid = $request->input('is_paid');
        $debtAmount = $request->input('debt_amount');
        $attributeId = $request->input('attribute_id');
        $vendorId = $request->input('vendor_id');
        $image = $request->file('image_url');

        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imagePath = $image->storeAs('public/petugas', $image->hashName());
            $imageUrl = Storage::url($imagePath);
        } else {
            $imageUrl = null;
        }

        $debts = Debt::create([
            'due_date' => $dueDate,
            'description' => $description,
            'is_paid' => $isPaid,
            'debt_amount' => $debtAmount,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId,
            'image_url' => $imageUrl
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

        if ($debt->image_url) {
            $imagePath = str_replace('/storage', 'public', $debt->image_url);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $debt->delete();

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " menghapus data hutang " . $debt->description . " dengan harga Rp " . $debt->debt_amount . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data hutang berhasil dihapus.']);
    }


    public function updateDebt(Request $request, $id)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $dueDate = $request->input('due_date');
        $description = $request->input('description');
        $isPaid = $request->input('is_paid');
        $debtAmount = $request->input('debt_amount');
        $attributeId = $request->input('attribute_id');
        $vendorId = $request->input('vendor_id');
        $image = $request->file('image_url');

        $debts = Debt::find($id);
        if (!$debts) {
            return response()->json(['message' => 'Debt not found'], 404);
        }

        $imagePath = $debts->image_url;
        if ($image) {
            if ($debts->image_url) {
                Storage::delete(str_replace('/storage', 'public', $debts->image_url));
            }

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/petugas', $filename);
            $imagePath = Storage::url($path);
        }

        $debts->update([
            'due_date' => $dueDate,
            'description' => $description,
            'is_paid' => $isPaid,
            'debt_amount' => $debtAmount,
            'year_id' => $activeYearId,
            'attribute_id' => $attributeId,
            'vendor_id' => $vendorId,
            'image_url' => $imagePath
        ]);

        Notification::create([
            'notification_content' => Auth::user()->name . " mengedit data hutang " . $description . " dengan harga Rp" .  $debtAmount . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $debts,
        ], 200);
    }
}
