<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
  public function createController(Request $request)
  {
    $request->session()->put('name', 'John Doe');
    $request->session()->put('data', [
      'id' => 1,
      'name' => 'John Doe',
      'email' => '9yYB8@example.com',
    ]);
    return 'Session created';
  }

  public function getSession(Request $request)
  {
    return $request->session()->all();
  }

  public function deleteAllSession(Request $request)
  {
    $request->session()->invalidate();
    return 'Session deleted';
  }
}
