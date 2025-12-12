<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt(['nama' => $credentials['nama'], 'password' => $credentials['password']])) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['nama' => 'Nama atau password salah']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}