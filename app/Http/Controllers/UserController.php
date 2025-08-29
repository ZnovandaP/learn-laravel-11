<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function loginPage()
  {
    return view('auth.login', [
      'title' => 'Login',
    ]);
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $attempt = Auth::attempt($credentials);

    if (!$attempt) {
      return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
      ]);
    }

    $request->session()->regenerate();
    return redirect()->intended(route('dashboard.index'));
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect(route('blog.guest'));
  }
}
