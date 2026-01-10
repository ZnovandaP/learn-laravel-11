<?php

use App\Exceptions\UserInactiveException;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserTestController;
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

Route::prefix('user-test')->controller(UserTestController::class)->group(function () {
  Route::post('/register', 'registerUser')->name('user-test.register');
  Route::post('/login', 'loginUser')->name('user-test.login');
  Route::middleware('auth.api.scratch')->group(function () {
    Route::get('/me', 'getCurrentUser')->name('user-test.current-user');
    Route::patch('/update', 'updateUser')->name('user-test.update-user');
    Route::delete('/logout', 'logoutUser')->name('user-test.logout-user');
  });
});

Route::prefix('contacts')->middleware('auth.api.scratch')->group(function () {
  Route::controller(ContactController::class)->group(function () {
    Route::post('/', 'createContact')->name('contact.create');
    Route::get('/', 'getContacts')->name('contact.list');
    Route::get('/{contactId}', 'getContactById')->name('contact.detail');
    Route::delete('/{contactId}', 'deleteContact')->name('contact.delete');
    Route::put('/{contactId}', 'updateContact')->name('contact.update');
  });

  Route::controller(AddressController::class)->group(function () {
    Route::post('/{contactId}/addresses', 'createAddress')->name('contact.address.create');
    Route::get('/{contactId}/addresses', 'getAddresses')->name('contact.address.list');
    Route::get('/{contactId}/addresses/{addressId}', 'getAddress')->name('contact.address.detail');
    Route::put('/{contactId}/addresses/{addressId}', 'updateAddress')->name('contact.address.update');
    Route::delete('/{contactId}/addresses/{addressId}', 'deleteAddress')->name('contact.address.delete');
  });
});