<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/author/{user:username}/posts', function (User $user) {
  //! menggunakan Route Model Binding
  // Lazy Eager Loading
  $user->posts->load(['author', 'category']);
  return view('author-posts', [
    "user" => $user,
    "count" => count($user->posts),
  ]);
});

//! menggunakan Route Model Binding
// ? bawaannya mencari berdasarkan id dari model post, slug ini customuize key binding
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

Route::get('/category/{category:slug}/posts', function (Category $category) {
  //! menggunakan Route Model Binding
  $category->posts->load(['author', 'category']);
  return view('category-posts', [
    "category" => $category,
    "count" => count($category->posts),
  ]);
});

Route::middleware('auth')->prefix('dashboard')->controller(PostController::class)->group(function () {
  Route::get('/', 'dashboard')->name('dashboard.index');
  Route::get('/edit/{post:slug}/', 'edit')->name('post.edit');
  Route::get('/create', 'create')->name('post.create');
  Route::post('/post', 'store')->name('post.store');
  Route::put('/update/{post:slug}', 'update')->name('post.update');
  Route::delete('/delete', 'destroy')->name('post.destroy');
});

Route::group([], function () {
  Route::get('/blog', [PostController::class, 'index'])->name('blog.guest');
  Route::get('/', function () {
    return view('home');
  })->name('home');
  Route::get('/contact', function () {
    return view('contact');
  });
  Route::inertia('/test', 'Home', ['foo' => 'bar']);
});

Route::controller(UserController::class)->group(function () {
  Route::get('/login', 'loginPage')->name('login');
  Route::post('/login', 'authenticate')->name('authenticate');
  Route::post('/logout', 'logout')->name('logout');
});


