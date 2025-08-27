<?php

use App\Exceptions\UserInactiveException;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ResponseController;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Route;

Route::prefix('hello')->group(function () {
  Route::get('/{name}', [HelloController::class, 'hallo'])->name('hello.indonesia');
  Route::get('/info/request', [HelloController::class, 'request'])->name('hello.request');
  Route::post('/input/request', [HelloController::class, 'input'])->name('hello.input');
});

Route::prefix('file')->group(function () {
  Route::post('/upload', [FileController::class, 'uploadFile'])->name('file.upload');
});
Route::prefix('response')->group(function () {
  Route::get('/text', [ResponseController::class, 'response'])->name('response.text');
  Route::get('/json', [ResponseController::class, 'jsonResponse'])->name('response.json');
  Route::get('/file', [ResponseController::class, 'fileResponse'])->name('response.file');
  Route::get('/download', [ResponseController::class, 'downloadFile'])->name('response.download');
});

Route::middleware([EncryptCookies::class, 'customKeyGroup'])->prefix('cookie')->group(function () {
  Route::get('/get', [CookieController::class, 'getCookie'])->name('cookie.get');
  Route::get('/set', [CookieController::class, 'setCookie'])->name('cookie.set');
  Route::get('/delete', [CookieController::class, 'deleteCookie'])->name('cookie.delete');
});

Route::get('/except', function () {
  throw new UserInactiveException(1);
});