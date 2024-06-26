<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Credit;
use App\Models\Attribute;
use App\Models\Role as ModelsRole;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userList()
    {
        $users = User::with('roles', 'categories')->select('uuid', 'name', 'email', 'user_email', 'number', 'nis', 'id', 'class_id', 'category_id', 'gender')->orderBy("updated_at", "DESC")->get();
        $classes = StudentClass::orderBy("class_name", "DESC")->get();
        $categories = Category::orderBy("category_name", "DESC")->get();
        $roles = ModelsRole::orderBy("name", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.index', compact('notifications', 'users', 'classes', 'categories', 'roles'));
    }

    public function profile()
    {
        $user = User::with('roles')->find(Auth::user()->id);
        $student = User::where('id', Auth::user()->id)->first();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        // return dd($student);
        return view('account.profile.index', compact('notifications', 'user', 'student'));
    }

    public function storeUser(Request $request)
    {
        $uuid = Uuid::uuid4()->toString();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'user_email' => ['nullable', 'string', 'min:5', 'max:255'],
            'number' => ['nullable', 'string', 'min:10', 'max:15'],
            'nis' => ['required'],
            'class_id' => ['required'],
            'category_id' => ['required'],
            'gender' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = [
            'uuid' => $uuid,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'nis' => $request->input('nis'),
            'class_id' => $request->input('class_id'),
            'category_id' => $request->input('category_id'),
            'gender' => $request->input('gender')
        ];

        if ($request->filled('user_email')) {
            $data['user_email'] = $request->input('user_email');
        }

        if ($request->filled('number')) {
            $data['number'] = $request->input('number');
        }

        $user = User::create($data);

        $userRole = $request->input('role_id');
        $user->assignRole($userRole);

        $activeYearId = Year::where('year_status', 'active')->value('id');

        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data user" . " " . $request->input('name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data user berhasil dibuat!'], 200);
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

        return response()->json(['message' => 'Data user berhasil dihapus!']);
    }

    public function editUser($uuid)
    {

        $user = User::find($uuid);

        $roleUser = DB::table('model_has_roles')->where('model_id', $uuid)->first();
        $classes = StudentClass::orderBy("class_name", "DESC")->get();
        $categories = Category::orderBy("category_name", "DESC")->get();
        $roles = ModelsRole::orderBy("name", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.edit', compact('notifications', 'user', 'classes', 'categories', 'roleUser', 'roles'));
    }

    public function updateUser(Request $request, $uuid)
    {
        $user = User::find($uuid);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'nis' => $request->input('nis'),
            'user_email' => $request->input('user_email'),
            'number' => $request->input('number'),
            'class_id' => $request->input('class_id'),
            'category_id' => $request->input('category_id'),
            'gender' => $request->input('gender')
        ]);

        $roleUser = DB::table('model_has_roles')->where('model_id', $uuid)->update([
            'role_id' => $request->input('role_id')
        ]);

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "mengedit data user" . " " . $user->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);
        return response()->json([
            'message' => 'Data user berhasil diedit!',
            'data' => $user,
        ], 200);
    }

    public function updatePassword(Request $request, $uuid)
    {

        $data = User::where('uuid', $uuid)->first();

        $id = $data->id;

        $user = User::find($id);

        if (!empty($request->emailUser)) {
            $user->update([
                'user_email' => $request->emailUser
            ]);
        }

        if (!empty($request->noTelpUser)) {
            $user->update([
                'number' => $request->noTelpUser
            ]);
        }

        if (!empty($request->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $activeYearId = Year::where('year_status', 'active')->value('id');
        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "telah memperbarui password" . " " . $user->name . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json([
            'message' => 'Data user berhasil diedit!',
            'data' => $user,
        ], 200);
    }
}
