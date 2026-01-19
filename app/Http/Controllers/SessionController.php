<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
  public function set(Request $request)
  {
    $key = $request->input('key');

    $request->session()->put($key, [
      'name' => fake()->name(),
      'country' => fake()->country()
    ]);

    return response([
      'success' => true
    ], 200);
  }

  public function get(Request $request)
  {
    $key = $request->input('key');

    return response([
      'data' => $request->session()->get($key)
    ], 200);
  }

  public function getAll(Request $request)
  {
    $request->session()->remove('rossi');
    return response([
      'data' => $request->session()->all(),
    ], 200);
  }
}
