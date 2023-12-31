<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Year;


class YearController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $years = Year::orderByRaw('year_status = "active" desc, updated_at desc')->select('year_name','year_semester','year_status','id')->get();
        return view('setting.year.index', compact('years', 'notifications'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year_name' => 'required',
            'year_semester' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $year = Year::create([
            'year_name' => $request->input('year_name'),
            'year_semester' => $request->input('year_semester'),
            'year_status' => "nonActive"
        ]);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data tahun ajaran" . " " . $request->input('year_name'),
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data Tahun berhasil ditambahkan!',
            'data' => $year,
        ], 201);
    }

    public function update($id)
    {
        try {
            Year::where('id', '<>', $id)->update(['year_status' => 'nonActive']);
            $year = Year::findOrFail($id);
            $year->year_status = 'active';
            $year->save();

            return response()->json([
                'success' => true,
                'message' => 'Status tahun ajaran berhasil diperbarui!',
                'year' => $year
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status tahun ajaran!'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $year = Year::find($id);
        if (!$year) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }
        $year->delete();
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data tahun ajaran" . " " . $year->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }


    public function currentYear(Request $request)
    {
        $newSemester = $request->input('year_semester');
        $newYearName = $request->input('year_name');

        if (!$this->isValidData($newSemester, $newYearName)) {
            return response()->json(['error' => 'Data yang diberikan tidak valid'], 422);
        }

        if (!$this->isDataExist($newSemester, $newYearName)) {
            return response()->json(['error' => 'Data tidak ditemukan di tabel'], 422);
        }

        Year::where('year_current', 'selected')->update(['year_current' => 'unSelected']);
        Year::where(['year_semester' => $newSemester, 'year_name' => $newYearName])->update(['year_current' => 'selected']);

        return response()->json(['message' => 'Data tahun ajaran berhasil diperbarui'], 200);
    }

    private function isValidData($semester, $yearName)
    {
        return !empty($semester) && !empty($yearName);
    }

    private function isDataExist($semester, $yearName)
    {
        return Year::where(['year_semester' => $semester, 'year_name' => $yearName])->exists();
    }
}

