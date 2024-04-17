<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Notification;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BahanController extends Controller
{
    public function index()
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $bahans = Bahan::orderBy("updated_at", "DESC")->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $years = Year::select('year_name','year_semester')->orderBy("updated_at", "DESC")->get();
        return view('spending.bahan.index', compact("students","notifications", "bahans", 'years'));
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        
        // Ambil invoice number terakhir
        $lastInvoiceNumber = Bahan::whereYear('updated_at', Carbon::now()->year)
                                        ->whereMonth('updated_at', Carbon::now()->month)
                                        ->orderBy('updated_at', 'DESC')
                                        ->value('increment');

        // dd($lastInvoiceNumber);

        $increment = 1;
        if ($lastInvoiceNumber != NULL) {
        $increment = $lastInvoiceNumber + 1;
        }

        // Format tanggal hari ini dalam format "ddMMyy"
        $todayDate = Carbon::now()->format('dmy');

        // Buat invoice number baru
        $invoiceNumber = 'BUY'. '-' . $todayDate . '-' . $increment;

        $bahan = Bahan::create([
            'spending_date' => $request->input('spending_date'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_price' => $request->input('spending_price'),
            'increment' => $increment,
            'invoice_number' => $invoiceNumber,
            'year_id' =>$activeYearId
        ]);

        return response()->json([
            'message' => 'Data kelas berhasil disimpan!',
            'data' => $bahan,
        ], 201);
    }

    public function destroy($id)
    {
        $bahan = Bahan::find($id);

        if (!$bahan) {
            return response()->json(['message' => 'Data Bahan tidak ditemukan.'], 404);
        }

        $bahan->delete();
        
        return response()->json(['message' => 'Data bahan berhasil dihapus.']);
    }

    public function update(Request $request, $id)
    {
        $class = Bahan::find($id);

        $class->update([
            'spending_date' => $request->input('spending_date'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_price' => $request->input('spending_price'),
            'invoice_number' => $request->input('invoice_number')
        ]);

        return response()->json([
            'message' => 'Data kelas berhasil diedit!',
            'data' => $class,
        ], 201);
    }
}
