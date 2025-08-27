<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
  public function uploadFile(Request $request)
  {
    // Handle file upload logic here
    // For example, you can use Storage facade to store the file
    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
      return 'File uploaded successfully: ' . $path;
    }

    return 'No file uploaded.';
  }
}
