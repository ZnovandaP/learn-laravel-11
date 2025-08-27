<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseController extends Controller
{
  public function response(): Response
  {
    return response('Hello, World!', 200)
      ->header('Content-Type', 'text/plain')
      ->withHeaders([
        'Author' => 'Zidane Novanda Putra',
        'App-Name' => 'Learn Laravel 11',
        'Version' => '1.0.0',
      ]);
  }

  public function jsonResponse(): JsonResponse
  {
    return response()->json([
      'message' => 'Hello, World!',
      'status' => 'success',
    ], 200, [
      'Author' => 'Zidane Novanda Putra',
      'App-Name' => 'Learn Laravel 11',
      'Version' => '1.0.0',
    ]);
  }

  public function fileResponse(): BinaryFileResponse
  {
    $filePath = public_path('storage/uploads/musashi.vagabond.jpeg');
    return response()->file($filePath, [
      'Content-Type' => 'image/jpeg',
      'Content-Disposition' => 'inline; filename="musashi.vagabond.jpeg"',
    ]);
  }

  public function downloadFile(): BinaryFileResponse
  {
    $filePath = public_path('storage/uploads/musashi.vagabond.jpeg');
    return response()->download($filePath, 'musashi.vagabond.jpeg', [
      'Content-Type' => 'image/jpeg',
    ]);
  }
}

