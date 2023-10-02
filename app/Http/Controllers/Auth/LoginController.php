<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // Update waktu login terakhir dalam session
        // Ambil waktu login terakhir dari pengguna
        $lastLogin = $user->last_login;

        // Hitung selisih waktu dalam menit antara waktu login terakhir dan waktu saat ini
        $minutesAgo = Carbon::parse($lastLogin)->diffInMinutes(Carbon::now());

        // Simpan selisih waktu dalam menit ke dalam sesi
        $request->session()->put('last_login', $this->formatMinutesAgo($minutesAgo));

        // Update waktu login terakhir dalam database
        $user->update(['last_login' => Carbon::now()]);
    }

    // Metode untuk mengubah selisih menit menjadi format "Baru saja," "xx menit yang lalu," atau "xx jam yang lalu"
    private function formatMinutesAgo($minutes)
    {
        if ($minutes < 1) {
            return 'Now';
        } elseif ($minutes < 60) {
            return $minutes . ' min ago';
        } else {
            $hours = floor($minutes / 60);
            return $hours . ' hour ago';
        }
    }
}
