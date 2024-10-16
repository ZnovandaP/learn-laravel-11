<?php

use App\Http\Controllers\PostController;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/blog', [PostController::class, 'index']);

//! menggunakan Route Model Binding
// ? bawaannya mencari berdasarkan id dari model post, slug ini customuize key binding
Route::get('/post/{post:slug}', [PostController::class, 'show'])->name('post.show');

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

Route::get('/contact', function () {

    return view('contact');
});
