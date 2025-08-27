<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieController extends Controller
{
  public function setCookie()
  {
    $cookieName = cookie('name', 'John Doe', 60, '/');
    return response('Cookie set')
      ->cookie($cookieName);
  }

  public function getCookie(Request $request)
  {
    return response()->json([
      'name' => $request->cookie('name', 'Guest'),
    ]);
  }

  public function deleteCookie()
  {
    return response('Cookie deleted')
      ->withoutCookie('name');
  }
}
