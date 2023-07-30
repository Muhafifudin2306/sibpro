<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class YearController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $years = Year::orderByRaw('year_status = "active" desc, created_at desc')->get();
        return view('setting.year.index', compact('years'));
    }

    public function store(Request $request)
    {
        $years = Year::create([
            'year_name' => $request->input('year_name'),
            'year_status' => "nonActive",
        ]);

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $years,
        ], 201); // 201 Created
    }
    public function update(Request $request, $id)
    {
        try {
            // Ambil semua tahun dan set status mereka menjadi "nonActive" (tidak aktif)
            Year::where('id', '<>', $id)->update(['year_status' => 'nonActive']);

            // Cari tahun berdasarkan ID dan set statusnya menjadi "active" (aktif)
            $year = Year::findOrFail($id);
            $year->year_status = 'active';
            $year->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Tahun berhasil diperbarui.',
                'year' => $year // Tambahkan tahun yang telah diperbarui dalam respon JSON
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status Tahun.'
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

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
}
