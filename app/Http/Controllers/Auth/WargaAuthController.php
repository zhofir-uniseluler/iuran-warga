<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WargaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.warga_login');
    }

    public function login(Request $request)
{
    $request->validate([
        'no_hp' => 'required|string|regex:/^[0-9]+$/',
        'password' => 'required|string',
    ]);

    $warga = Warga::attemptLogin($request->no_hp, $request->password);

    if ($warga) {
        Auth::guard('warga')->login($warga);
        return redirect()->intended('/warga/dashboard');
    }

    return back()->withErrors([
        'no_hp' => 'Nomor HP atau password salah',
    ])->withInput();
}
    public function logout()
    {
        Auth::guard('warga')->logout();
        return redirect('/warga/login');
    }

    public function showRegisterForm()
    {
        return view('auth.warga_register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:warga',
            'no_rumah' => 'required|string|max:10',
            'blok_rt' => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $warga = Warga::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'no_rumah' => $request->no_rumah,
            'blok_rt' => $request->blok_rt,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('warga')->login($warga);

        return redirect()->route('warga.dashboard')->with('success', 'Registrasi berhasil!');
    }
}