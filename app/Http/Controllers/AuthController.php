<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login()
  {
    return view('dashboard.auth.login');
  }

  public function authenticate(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);
    if (Auth::attempt($request->only('email', 'password'))) {
      $request->session()->regenerate();
      return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }
    return back()->with('error', 'Login gagal, silahkan coba lagi!');
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->route('login')->with('success', 'Logout berhasil!');
  }
}
