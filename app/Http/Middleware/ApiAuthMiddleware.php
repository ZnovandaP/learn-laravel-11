<?php

namespace App\Http\Middleware;

use App\Models\UserTest;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $token = $request->header('Authorization');

    if (!$token) {
      throw new HttpResponseException(response()->json([
        'message' => 'Authorization token is missing',
      ], 401));
    }

    $user = UserTest::where('token', $token)->first();
    if (!$user) {
      throw new HttpResponseException(response()->json([
        'message' => 'Authorization token is invalid',
      ], 401));
    }

    return $next($request);
  }
}
