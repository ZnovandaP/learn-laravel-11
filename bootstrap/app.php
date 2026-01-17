<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      HandleInertiaRequests::class,
    ]);
    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (AuthenticationException $e, Request $request) {
      $guard = $e->guards()[0] ?? null;
      $unauthenticatedResponse = response([
        'message' => 'Unauthenticated.',
        'code' => 401,
      ], 401);

      if ($request->expectsJson()) {
        return $unauthenticatedResponse;
      }

      return match ($guard) {
        'token' => $unauthenticatedResponse,
        'simple-token' => $unauthenticatedResponse,
        default => redirect()->guest(route('login')),
      };
    });
  })->create();
