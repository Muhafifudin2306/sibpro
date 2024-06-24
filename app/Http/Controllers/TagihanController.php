<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Payment;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Year;


class TagihanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:SPP,Daftar Ulang',
            'status' => 'required|in:Paid,Unpaid',
            // 'credit_id' => 'nullable|exists:credits,id',
            // 'attribute_id' => 'nullable|exists:attributes,id',
        ]);

        $activeYearId = Year::where('year_current', 'selected')->value('id');

        $price = 0;

        $uuid = Str::uuid();

        if ($request->input('type') === 'SPP') {
            $price = Credit::find($request->input('credit_id'))->credit_price;
        } elseif ($request->input('type') === 'Daftar Ulang') {
            $price = Attribute::find($request->input('attribute_id'))->attribute_price;
        }

        Payment::create([
            'uuid' => $uuid,
            'user_id' =>  $request->input('user_id'),
            'type' => $request->input('type'),
            'credit_id' => $request->input('type') === 'SPP' ? $request->input('credit_id') : null,
            'attribute_id' => $request->input('type') === 'Daftar Ulang' ? $request->input('attribute_id') : null,
            'status' => $request->input('status'),
            'year_id' => $activeYearId,
            'price' => $price,
        ]);

        return response()->json(['success' => true, 'message' => 'Data tagihan berhasil dibuat']);
    }
}
