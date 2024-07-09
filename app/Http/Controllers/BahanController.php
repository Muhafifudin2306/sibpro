<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Bahan;
use App\Models\Notification;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BahanController extends Controller
{
    public function index()
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $bahans = Bahan::orderBy("updated_at", "DESC")->where('year_id', $activeYearId)->get();
        $students = StudentClass::orderBy("class_name", 'ASC')->get();
        $years = Year::select('year_name', 'year_current')->orderBy("updated_at", "DESC")->get();
        return view('spending.bahan.index', compact("students", "notifications", "bahans", 'years'));
    }

    public function store(Request $request)
    {
        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $lastInvoiceNumber = Bahan::whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->orderBy('updated_at', 'DESC')
            ->value('increment');

        $increment = 1;
        if ($lastInvoiceNumber != NULL) {
            $increment = $lastInvoiceNumber + 1;
        }

        // Format tanggal hari ini dalam format "ddMMyy"
        $todayDate = Carbon::now()->format('dmy');

        $invoiceNumber = 'BUY' . '-' . $todayDate . '-' . $increment;

        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imagePath = $image->storeAs('public/petugas', $image->hashName());
            $imageUrl = Storage::url($imagePath);
        } else {
            $imageUrl = null;
        }

        $bahan = Bahan::create([
            'spending_date' => $request->input('spending_date'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_price' => $request->input('spending_price'),
            'increment' => $increment,
            'invoice_number' => $invoiceNumber,
            'year_id' => $activeYearId,
            'image_url' => $imageUrl
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

        if ($bahan->image_url) {
            $imagePath = str_replace('/storage', 'public', $bahan->image_url);
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $bahan->delete();

        return response()->json(['message' => 'Data bahan berhasil dihapus.']);
    }


    public function update(Request $request, $id)
    {
        $bahan = Bahan::find($id);

        $imagePath = $bahan->image_url;
        if ($request->hasFile('image_url')) {
            if ($bahan->image_url) {
                Storage::delete('public/petugas/' . basename($bahan->image_url));
            }
            $image = $request->file('image_url');
            $image->storeAs('public/petugas', $image->hashName());
            $imagePath = Storage::url('public/petugas/' . $image->hashName());
        }

        $bahan->update([
            'spending_date' => $request->input('spending_date'),
            'spending_desc' => $request->input('spending_desc'),
            'spending_price' => $request->input('spending_price'),
            'invoice_number' => $request->input('invoice_number'),
            'image_url' => $imagePath
        ]);

        return response()->json([
            'message' => 'Data kelas berhasil diedit!',
            'data' => $bahan,
        ], 201);
    }
}
