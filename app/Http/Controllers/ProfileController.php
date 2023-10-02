<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    public function users()
    {
        $users = User::with('roles')->get();
        $roles = DB::table('roles')->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.index', compact('notifications', 'users', 'roles'));
    }

    public function profile()
    {
        $user = User::with('roles')->find(Auth::user()->id);
        $student = Student::where('user_id', Auth::user()->id)->first();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.profile.index', compact('notifications', 'user', 'student'));
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        $studentRole = Role::where('name', $request->input('role_name'))->first();
        $user->assignRole($studentRole);
    }
}
