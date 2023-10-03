<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Permission;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function permissionList()
    {
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $permissions = Permission::orderBy("updated_at", "DESC")->get();
        return view("security.permission.index", compact('notifications', 'permissions'));
    }

    public function storePermission(Request $request)
    {
        // Dapatkan ID tahun aktif dari tabel Year
        $activeYearId = Year::where('year_status', 'active')->value('id');

        // Simpan data atribut untuk tahun aktif
        $permissions = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web'
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Membuat Data permission" . " " . $request->input('category_name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $permissions,
        ], 201);
    }

    public function destroyPermission($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $permission->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Menghapus Data permission" . " " . $permission->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::find($id);

        $permission->update([
            "name" =>  $request->input('name'),
        ]);
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "Mengedit Data Permission" . " " . $permission->attribute_name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $permission,
        ], 201);
    }
}
