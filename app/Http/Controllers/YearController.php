<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index()
    {
        $years = Year::orderByRaw('year_status = "active" desc, created_at desc')->get();
        return view('setting.year.index', compact('years'));
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
}
