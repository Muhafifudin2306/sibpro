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
                    $order = Payment::find($request->order_id);

                    if ($order) {
                        $itemDetails = json_decode($order->item_details, true);

                        foreach ($itemDetails as &$item) {
                            // Memeriksa apakah $item adalah array
                            if (is_array($item) && array_key_exists('id', $item) && $item['id'] == $request->item_details['id']) {
                                // Update status dan harga berdasarkan item_details
                                $item['status'] = 'Paid';
                                $item['price'] = $request->item_details['price'];
                            }
                        }

                        // Update model pembayaran dengan item_details yang sudah diperbarui
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

            } else {
                \Log::warning('Hash tidak cocok. Ada potensi perubahan data tidak sah.');
            }
        } else {
            \Log::error('Data callback tidak lengkap atau tidak valid.');
        }
    }
}
