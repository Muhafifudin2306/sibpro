<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attribute;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
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
        $users = User::with('roles')->get();
        $classes = StudentClass::orderBy("updated_at", "DESC")->get();
        $categories = Category::orderBy("updated_at", "DESC")->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.users.index', compact('notifications', 'users', 'classes', 'categories'));
    }

    public function profile()
    {
        $user = User::with('roles')->find(Auth::user()->id);
        $student = User::where('id', Auth::user()->id)->first();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('account.profile.index', compact('notifications', 'user', 'student'));
    }

    public function storeUser(Request $request)
    {
        $uuid = Uuid::uuid4()->toString();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'nis' => ['required'],
            'class_id' => ['required'],
            'category_id' => ['required'],
            'gender' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'uuid' => $uuid,
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

        
        // Retrieve id_credit based on category_id
        $id_credits = Category::find($request->input('category_id'))->credits()->pluck('credit_id');

        $activeYearId = Year::where('year_status', 'active')->value('id');

         // Attach user to credits in user_has_credit table with id_year
         foreach ($id_credits as $creditId) {
            $uuidTwo = Uuid::uuid4()->toString();
            
            // Generate a unique invoice number with 7 digits and 5 letters
            $invoiceNumber = $this->generateInvoiceNumberCredit();

            $user->credits()->attach($creditId, [
                'year_id' => $activeYearId,
                'uuid' => $uuidTwo,
                'invoice_number' => $invoiceNumber,
                'created_at' => now(), // Add created_at timestamp
                'updated_at' => now(), // Add updated_at timestamp
            ]);
        }

        // Retrieve id_credit based on attribute_id
        $id_attributes = Attribute::find($request->input('category_id'))->attributes()->pluck('attribute_id');

        $activeYearId = Year::where('year_status', 'active')->value('id');

         // Attach user to credits in user_has_credit table with id_year
        //$user->attributes()->attach($id_attributes, ['year_id' => $activeYearId]);

        foreach ($id_attributes as $attributeId) {
            $uuidThree = Uuid::uuid4()->toString();
            
            // Generate a unique invoice number with 7 digits and 5 letters
            $invoiceNumber = $this->generateInvoiceNumberEnrollment();

            $user->attributes()->attach($attributeId, [
                'year_id' => $activeYearId,
                'uuid' => $uuidThree,
                'invoice_number' => $invoiceNumber,
                'created_at' => now(), // Add created_at timestamp
                'updated_at' => now(), // Add updated_at timestamp
            ]);
        }


        $years = Year::find($activeYearId);

        Notification::create([
            'notification_content' => Auth::user()->name . " " . "membuat data user" . " " . $request->input('name') . " " . "pada tahun ajaran" . " " . $years->year_name,
            'notification_status' => 0
        ]);

        return response()->json(['message' => 'Data user berhasil dibuat!'], 200);
    }
    private function generateInvoiceNumberCredit()
    {
        // Generate 7 random digits
        $digits = mt_rand(1000000, 9999999);

        // Generate 5 random letters
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

        // Concatenate digits and letters
        $invoiceNumber = "SPP"."-".$digits ."-". $letters;

        return $invoiceNumber;
    }

    private function generateInvoiceNumberEnrollment()
    {
        // Generate 7 random digits
        $digits = mt_rand(1000000, 9999999);

        // Generate 5 random letters
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

        // Concatenate digits and letters
        $invoiceNumber = "DU"."-".$digits ."-". $letters;

        return $invoiceNumber;
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
            'message' => 'Data user berhasil diedit!',
            'data' => $user,
        ], 200);
    }
}
