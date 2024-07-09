<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Notification;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use App\Models\ExternalSpending;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $externals = ExternalSpending::where('is_operational', 1)->where('year_id', $activeYearId)->latest()->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.operasional.index', compact('students', 'notifications', 'years', 'externals'));
    }

    public function indexNonOperasional()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $externals = ExternalSpending::where('is_operational', 0)->where('year_id', $activeYearId)->get();
        $years = Year::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.non-operasional.index', compact('students', 'notifications', 'years', 'externals'));
    }

    public function storeOperasional(Request $request)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required|date',
            'spending_type' => 'required',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/petugas', $imageName);
        } else {
            $imageName = null;
        }

        $operational = ExternalSpending::create([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_date' => $request->input('spending_date'),
            'spending_type' => $request->input('spending_type'),
            'is_operational' => 1,
            'year_id' => $activeYearId,
            'image_url' => $imageName
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " membuat data pengeluaran operasional dengan deskripsi " . $request->spending_desc . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $operational
        ], 201);
    }

    public function storeNonOperasional(Request $request)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required|date',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imagePath = $image->storeAs('public/non_oprerational', $image->hashName());
            $imageUrl = Storage::url($imagePath);
        } else {
            $imageUrl = null;
        }

        $nonOperational = ExternalSpending::create([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_date' => $request->input('spending_date'),
            'spending_type' => 'Biaya Non-Operasional',
            'is_operational' => 0,
            'year_id' => $activeYearId,
            'image_url' => $imageUrl
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " membuat data pengeluaran non-operasional dengan deskripsi " . $request->spending_desc . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $nonOperational
        ], 201);
    }

    public function destroy($id)
    {
        $external = ExternalSpending::find($id);

        if (!$external) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        if ($external->image_url) {
            $imagePath = str_replace('/storage', 'public', $external->image_url);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $external->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }


    public function updateOperasional(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required|date',
            'spending_type' => 'required',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $externalSpending = ExternalSpending::findOrFail($id);

        $imagePath = $externalSpending->image_url;
        if ($request->hasFile('image_url')) {
            if ($externalSpending->image_url) {
                Storage::delete('public/operational/' . basename($externalSpending->image_url));
            }
            $image = $request->file('image_url');
            $image->storeAs('public/operational', $image->hashName());
            $imagePath = Storage::url('public/operational/' . $image->hashName());
        }

        $externalSpending->update([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_date' => $request->input('spending_date'),
            'spending_type' => $request->input('spending_type'),
            'is_operational' => 1,
            'year_id' => $activeYearId,
            'image_url' => $imagePath
        ]);


        Notification::create([
            'notification_content' => Auth::user()->name . " mengupdate data pengeluaran operasional dengan deskripsi " . $request->input('spending_desc') . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $externalSpending
        ], 200);
    }

    public function updateNonOperasional(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'spending_price' => 'required|numeric',
            'spending_desc' => 'required|max:255',
            'spending_date' => 'required|date',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $years = Year::find($activeYearId);

        $externalSpending = ExternalSpending::findOrFail($id);

        $imagePath = $externalSpending->image_url;
        if ($request->hasFile('image_url')) {
            if ($externalSpending->image_url) {
                Storage::delete('public/non_operational/' . basename($externalSpending->image_url));
            }
            $image = $request->file('image_url');
            $image->storeAs('public/non_operational', $image->hashName());
            $imagePath = Storage::url('public/non_operational/' . $image->hashName());
        }

        $externalSpending->update([
            'spending_price' => $request->input('spending_price'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_date' => $request->input('spending_date'),
            'image_url' => $imagePath
        ]);



        Notification::create([
            'notification_content' => Auth::user()->name . " memperbarui data pengeluaran non-operasional dengan deskripsi " . $request->input('spending_desc') . " pada tahun ajaran " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $externalSpending
        ], 200);
    }
}
