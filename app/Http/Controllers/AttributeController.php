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
        $attributes = Attribute::where('year_id', $activeYearId)->orderBy("created_at", "DESC")->get();

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

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data Atribut" . " " . $request->input('attribute_name') . " " . "dengan harga" . " " . "Rp" . $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $attributes,
        ], 201); // 201 Created
    }

    public function update(Request $request, $id)
    {
        $attributes = Attribute::find($id);

        $attributes->update([
            "attribute_price" =>  $request->input('attribute_price'),
            'user_id' => Auth::user()->id
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Atribut" . " " . $attributes->attribute_name . " " . "dengan harga" . " " . "Rp" .  $request->input('attribute_price') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $attributes,
        ], 201); // 201 Created
    }

    public function destroy($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $attribute->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data Atribut" . " " . $attribute->attribute_name . " " . "dengan harga" . " " . "Rp" . $attribute->attribute_price . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_staus' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }
}
