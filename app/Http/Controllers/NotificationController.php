<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $notification_list = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->get();
        return view('notification', compact('notifications', 'notification_list'));
    }
    public function store()
    {
        $notifications = Notification::all(); // Fetch all notifications
        foreach ($notifications as $notification) {
            $notification->update(['notification_status' => 1]); // Update each notification's status
        }

        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $notifications,
        ], 201); // 201 updated
    }

    public function update($id)
    {
        try {
            // Cari tahun berdasarkan ID dan set statusnya menjadi "1" (aktif)
            $notification = notification::findOrFail($id);
            $notification->notification_status = '1';
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Notifikasi berhasil diperbarui.',
                'notification' => $notification // Tambahkan tahun yang telah diperbarui dalam respon JSON
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status Tahun.'
            ], 500);
        }
    }
}
