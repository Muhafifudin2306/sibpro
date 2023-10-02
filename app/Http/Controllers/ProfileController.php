<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = DB::table('roles')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.profile.index', compact('notifications', 'users', 'roles'));
    }

    public function store(Request $request)
    {
        // Membuat pengguna
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        // Mengambil peran "Student" dari database
        $studentRole = Role::where('name', $request->input('role_name'))->first();

        // Mengasign peran "Student" kepada pengguna yang baru dibuat
        $user->assignRole($studentRole);
    }
}
