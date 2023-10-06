<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userList()
    {
        $users = User::with('roles')->get();
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();
        $categories = Category::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.index', compact('notifications', 'users', 'classes', 'categories'));
    }

    public function profile()
    {
        $user = User::with('roles')->find(Auth::user()->id);
        $student = Student::where('user_id', Auth::user()->id)->first();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.profile.index', compact('notifications', 'user', 'student'));
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nis' => ['required'],
            'class_id' => ['required'],
            'category_id' => ['required'],
            'gender' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'nis' => $request->input('nis'),
            'class_id' => $request->input('class_id'),
            'category_id' => $request->input('category_id'),
            'gender' => $request->input('gender')
        ]);

        $studentRole = Role::where('name', 'Student')->first();
        $user->assignRole($studentRole);
        $activeYearId = Year::where('year_status', 'active')->value('id');

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data user" . " " . $request->input('name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Tambah data berhasil'], 200);
    }

    public function destroyUser($id)
    {
        $user = user::find($id);

        if (!$user) {
            return response()->json(['message' => 'Data user tidak ditemukan.'], 404);
        }

        $user->delete();
        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "menghapus data user" . " " . $user->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data Tahun berhasil dihapus.']);
    }

    public function editUser($id)
    {
        $user = User::find($id);
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();
        $categories = Category::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.edit', compact('notifications', 'user', 'classes', 'categories'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        $user->update([
            "name" =>  $request->input('name'),
            "email" =>  $request->input('email'),
            "gender" =>  $request->input('gender'),
            "class_id" =>  $request->input('class_id'),
            "category_id" =>  $request->input('category_id'),
            "nis" =>  $request->input('nis'),
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data user" . " " . $user->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $user,
        ], 200);
    }
}
