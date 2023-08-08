<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Year;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    public function __construct()
    {
        // Tambahkan middleware autentikasi ke metode 'store'
        $this->middleware('auth')->only('store');
    }
    public function index()
    { // Dapatkan ID tahun yang aktif
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Filter data atribut berdasarkan ID tahun aktif
        $attributes = Attribute::where('year_id', $activeYearId)->get();

        $notifications = Notification::orderBy("created_at", 'DESC')->limit(10)->get();

        return view('setting.attribute.index', compact('attributes', 'notifications'));
    }

    public function store(Request $request)
    {
        // Dapatkan ID tahun aktif dari tabel Year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Simpan data atribut untuk tahun aktif
        $attributes = Attribute::create([
            'attribute_name' => $request->input('attribute_name'),
            'attribute_price' => $request->input('attribute_price'),
            'year_id' => $activeYearId,
            'user_id' => Auth::user()->id
        ]);

        // Dapatkan tahun-tahun nonaktif dari tabel Year
        $nonActiveYears = Year::where('year_status', 'nonActive')->get();

        // Looping untuk setiap tahun nonaktif
        foreach ($nonActiveYears as $nonActiveYear) {
            // Simpan data atribut untuk setiap tahun nonaktif
            $attributes = Attribute::create([
                'attribute_name' => $request->input('attribute_name'),
                'attribute_price' => $request->input('attribute_price'),
                'year_id' => $nonActiveYear->id,
                'user_id' => Auth::user()->id
            ]);
        }

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $attributes,
        ], 201); // 201 Created
    }
}
