<?php

use App\Exceptions\UserInactiveException;
use Illuminate\Foundation\Application;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SimpleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    api: __DIR__ . '/../routes/api.php',
    apiPrefix: '',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      HandleInertiaRequests::class,
    ]);

    $middleware->alias([
      'customKey' => SimpleMiddleware::class
    ]);

    $middleware->group('customKeyGroup', [
      'customKey:secret,401',
    ]);

  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (UserInactiveException $exception, Request $request) {
      return response()->json([
        'message' => $exception->getMessage(),
        'user_id' => $exception->getUserId(),
      ], 403);
    });
  })->create();
