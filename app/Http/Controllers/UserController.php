<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
  public function login(Request $request)
  {
    $validate = $request->validateWithBag('login', [
      'email' => 'required|email',
      'password' => 'required|min:6',
    ]);

    if (Auth::attempt($validate, $request->boolean('remember'))) {
      $request->session()->regenerate();

      $token = Str::uuid()->toString();
      $user = Auth::user();
      $user->token = $token;
      $user->save();

      return response()->json([
        'message' => 'Login successful',
        'token' => $token
      ], 200);
    }
  }

  public function current()
  {
    return response()->json([
      'data' => [
        'user' => Auth::user()
      ]
    ], 200);
  }
}
