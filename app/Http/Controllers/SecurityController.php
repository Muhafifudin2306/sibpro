<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Permission;
use App\Models\Role;
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
    public function roleList()
    {
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        $roles = Role::with('permissions')->orderBy("updated_at", "DESC")->get();
        $permissions = Permission::orderBy("updated_at", "DESC")->get();

        return view("security.role.index", compact('roles', 'notifications', 'permissions'));
    }

    public function storePermission(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $permissions = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web'
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data permission" . " " . $request->input('name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $permissions,
        ], 201);
    }

    public function storeRole(Request $request)
    {
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $roles = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web'
        ]);

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data role" . " " . $request->input('name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $roles,
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
            'notification_content' => Auth::user()->name . " " . "menghapus data permission" . " " . $permission->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }

    public function destroyRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Data Tahun tidak ditemukan.'], 404);
        }

        $role->delete();
        $role->permissions()->detach();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data role" . " " . $role->name . " " . "pada tahun ajaran" . " " . $years->year_name,
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
            'notification_content' => Auth::user()->name . " " . "mengedit data permission" . " " . $permission->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $permission,
        ], 201);
    }

    public function editRole($id)
    {
        $role = Role::find($id);
        $permissions = $role->permissions;
        $allPermissions = Permission::all();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();

        return view('security.role.edit', compact('role', 'permissions', 'allPermissions', 'notifications'));
    }
    public function updateRole(Request $request, $id)
    {
        $roles = role::find($id);

        $roles->update([
            "name" =>  $request->input('name'),
        ]);

        $permissionIds = $request->input('permission_id');

        $roles->permissions()->sync($permissionIds);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data role" . " " . $roles->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data inserted successfully',
            'data' => $roles,
        ], 201);
    }
}
