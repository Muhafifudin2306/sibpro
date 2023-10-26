<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserHasCredit;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class RegisterController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = RouteServiceProvider::HOME;
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nis' => ['required'],
            'class_id' => ['required'],
            'category_id' => ['required'],
            'gender' => ['required']
        ]);
    }
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nis' => $data['nis'],
            'class_id' => $data['class_id'],
            'category_id' => $data['category_id'],
            'gender' => $data['gender'],
        ]);
        $studentRole = Role::where('name', 'Student')->first();
        $user->assignRole($studentRole);

        $selectedCredits = $user->categories->credits->pluck('id')->toArray();

        foreach ($selectedCredits as $creditId) {
            UserHasCredit::create([
                'user_id' => $user->id,
                'credit_id' => $creditId,
                'status' => 'Unpaid',
                'credit_price' => 0
            ]);
        }

        return $user;
    }
}
