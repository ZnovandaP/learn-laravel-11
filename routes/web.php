<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\User;
use App\Models\Category;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/blog', [PostController::class, 'index']);

//! menggunakan Route Model Binding
// ? bawaannya mencari berdasarkan id dari model post, slug ini customuize key binding
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

Route::group(['prefix' => 'dashboard-blog'], function () {
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

Route::get('/contact', function () {

  return view('contact');
});

require __DIR__ . '/auth.php';
