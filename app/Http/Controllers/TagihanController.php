<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Year;
use App\Models\Credit;
use App\Models\Payment;
use App\Models\Attribute;
use App\Models\Notification;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagihanController extends Controller
{    
    public function store(Request $request) 
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:SPP,Daftar Ulang',
            'status' => 'required|in:Paid,Unpaid',
            'credit_id' => 'nullable|exists:credits,id',
            'attribute_id' => 'nullable|exists:attributes,id',
        ]);

        $price = 0;

        // dd($request->input('credit_id'));

        if ($request->input('type') === 'SPP') {
            $price = Credit::find($request->input('credit_id'))->credit_price;
        } elseif ($request->input('type') === 'Daftar Ulang') {
            $price = Attribute::find($request->input('attribute_id'))->attribute_price;
        }

        Payment::create([
            'user_id' =>  $request->input('user_id'),
            'type' => $request->input('type'),
            'credit_id' => $request->input('type') === 'SPP' ? $request->input('credit_id') : null,
            'attribute_id' => $request->input('type') === 'Daftar Ulang' ? $request->input('attribute_id') : null,
            'status' => $request->input('status'),
            'price' => $price,
        ]);

        return redirect('enrollment')->with('success', 'Data tagihan berhasil dibuat');
    }
}
