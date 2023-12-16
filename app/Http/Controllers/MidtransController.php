<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Payment;

class MidtransController extends Controller
{

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        if (!empty($request->order_id) && !empty($request->status_code) && !empty($request->gross_amount)) {

            $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

            if ($hashed == $request->signature_key) {
                if ($request->transaction_status == 'settlement') {
                    // Assuming 'item_details' is a JSON string in the database
                    $order = Payment::find($request->order_id);

                    if ($order) {
                        $itemDetails = json_decode($order->item_details, true);

                        foreach ($itemDetails as &$item) {
                            if ($item['id'] == $request->item_details['id']) {
                                // Update the payment status and price based on item_details
                                $item['status'] = 'Paid';
                                $item['price'] = $request->item_details['price'];
                            }
                        }

                        // Update the payment model with the updated item_details
                        $order->update([
                            'status' => 'Paid',
                            'price' => $request->item_details['price'],
                            'item_details' => json_encode($itemDetails),
                        ]);

                        \Log::info('Pembayaran berhasil. ID Pesanan: ' . $request->order_id);
                    } else {
                        \Log::warning('Pesanan tidak ditemukan.');
                    }
                }
                \Log::info('Callback Midtrans berhasil diproses.');
            } else {
                \Log::warning('Hash tidak cocok. Ada potensi perubahan data tidak sah.');
            }
        } else {
            \Log::error('Data callback tidak lengkap atau tidak valid.');
        }
    }
}
