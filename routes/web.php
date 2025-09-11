<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SessionController;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard'], function () {
  Route::get('/', [PostController::class, 'dashboard'])->name('dashboard.index');
  Route::get('/edit/{post:slug}/', [PostController::class, 'edit'])->name('post.edit');
  Route::get('/create', [PostController::class, 'create'])->name('post.create');
  Route::post('/post', [PostController::class, 'store'])->name('post.store');
  Route::put('/update/{post:slug}', [PostController::class, 'update'])->name('post.update');
  Route::delete('/delete', [PostController::class, 'destroy'])->name('post.destroy');
});

Route::get('/author/{user:username}/posts', function (User $user) {
  //! menggunakan Route Model Binding
  // Lazy Eager Loading
  $user->posts->load(['author', 'category']);
  return view('author-posts', [
    "user" => $user,
    "count" => count($user->posts),
  ]);
});

Route::inertia('/test', 'Home', ['foo' => 'bar']);

Route::get('/category/{category:slug}/posts', function (Category $category) {
  //! menggunakan Route Model Binding

  $category->posts->load(['author', 'category']);

  return view('category-posts', [
    "category" => $category,
    "count" => count($category->posts),
  ]);
});

Route::get('/', function () {
  return view('home');
});

Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

//! menggunakan Route Model Binding
// ? bawaannya mencari berdasarkan id dari model post, slug ini customuize key binding
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

Route::get('/contact', function () {
  return view('contact');
});

Route::group(['prefix' => 'session'], function () {
  Route::get('/set', [SessionController::class, 'createController'])->name('session.create');
  Route::get('/flush', [SessionController::class, 'deleteAllSession'])->name('session.deleteAll');
  Route::get('/', [SessionController::class, 'getSession'])->name('session.get');
});

Route::fallback(function () {
  return redirect()->route('blog.index');
})->name('fallback');

Route::view('/learning-blade', 'learning.learn', [
  'hobbies' => ['Coding', 'Reading', 'Traveling'],
  'helloService' => new \App\Demo\HelloServiceIndonesia('Novanda')
]);