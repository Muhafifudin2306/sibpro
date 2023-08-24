<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;


class YearController extends Controller
{
    // Requied user have to login 
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Year View List
    public function index()
    {
        // Notification get data
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();

        // Year get data
        $years = Year::orderByRaw('year_status = "active" desc, updated_at desc')->get();

        // Parsing data to vie
        return view('setting.year.index', compact('years', 'notifications'));
    }
    // Year View List


    // Year Store Data
    public function store(Request $request)
    {
        // Create Data Year Table
        $validator = Validator::make($request->all(), [
            'year_name' => [
                'required'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $year = Year::create([
            'year_name' => $request->input('year_name'),
            'year_status' => "nonActive",
            'user_id' => Auth::user()->id
        ]);

        // Notification create to store year data
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Tahun" . " " . $request->input('year_name'),
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $year,
        ], 201);
    }
    // Year Store Data


    // Year Update Data
    public function update($id)
    {
        try {
            Year::where('id', '<>', $id)->update(['year_status' => 'nonActive']);
            $year = Year::findOrFail($id);
            $year->year_status = 'active';
            $year->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Tahun berhasil diperbarui.',
                'year' => $year
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status Tahun.'
            ], 500);
        }
    }
    // Year Update Data

    // Year Delete Data
    public function destroy($id)
    {
        $year = Year::find($id);

        if (!$year) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $year->delete();

        // Create Notification Year Data Delete
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Tahun" . " " . $year->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
    // Year Delete Data
}
