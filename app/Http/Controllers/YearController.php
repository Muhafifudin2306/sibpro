<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Attribute;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, created_at DESC")->limit(10)->get();
        $years = Year::orderByRaw('year_status = "active" desc, created_at desc')->get();
        return view('setting.year.index', compact('years', 'notifications'));
    }

    public function store(Request $request)
    {
        // Buat data di tabel Year
        $year = Year::create([
            'year_name' => $request->input('year_name'),
            'year_status' => "nonActive",
            'user_id' => Auth::user()->id
        ]);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Tahun" . " " . $request->input('year_name'),
            'notification_staus' => 0
        ]);

        // Mendapatkan harga atribut tertinggi dari setiap jenis atribut yang sudah ada
        $highestPricesByType = Attribute::groupBy('attribute_name')
            ->select('attribute_name', DB::raw('MAX(attribute_price) as max_price'))
            ->get();

        // Mendapatkan data atribut yang sudah ada di tabel Attribute
        $existingAttributes = Attribute::all();

        // Looping untuk menduplikasi data atribut ke tahun baru
        foreach ($existingAttributes as $existingAttribute) {
            // Cari harga tertinggi untuk jenis atribut yang sesuai
            $highestPrice = $highestPricesByType->where('attribute_name', $existingAttribute->attribute_name)->first()->max_price;

            // Cari atau buat atribut baru di tahun baru dan set harga sesuai harga tertinggi
            Attribute::firstOrCreate([
                'attribute_name' => $existingAttribute->attribute_name,
                'attribute_price' => $highestPrice,
                'year_id' => $year->id,
                'user_id' => Auth::user()->id
            ]);
        }

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $year,
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
        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Tahun" . " " . $year->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
}
