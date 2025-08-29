<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Services\PostService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
  public function __construct(private PostService $postService)
  {
    $this->postService = $postService;
  }
  public function index(Post $post)
  {
    /* 
        ! Eager Loading, solving N + 2 Problem (N = jumlah data, 2 = 3 query {posts, Rel author, Rel category})
        * $posts =  Post::with(['author', 'category'])->latest()->get();
    */
    $posts = $post::filter(request(['title', 'category', 'author']))
      ->latest()
      ->paginate(15)
      ->withQueryString();

    return view('blog', compact('posts'));
  }

  public function show(Post $post)
  {
    return view('post', compact('post'));
  }

  public function dashboard(Post $post, Category $category, User $user)
  {
    $name = Auth::user()->name;
    $title = "Dashboard Managament Post, Hello $name";

    $posts = $post::filter(request(['title', 'category', 'author']))
      ->latest()
      ->paginate(15)
      ->withQueryString();

    $categories = $category->all();
    $users = $user->all();

    return view('dashboard.index', compact('posts', 'categories', 'users', 'title'));
  }

  public function edit(Post $post, Category $category, User $user)
  {
    $title = "Edit Post: {$post->title}";

    $categories = $category->all();
    $users = $user->all();
    return view('dashboard.edit', compact('title', 'categories', 'post', 'users'));
  }

  public function create(Category $category, User $user)
  {
    $title = "Create/Add New Post";

    $categories = $category->all();
    $users = $user->all();
    return view('dashboard.create', compact('title', 'categories', 'users'));
  }

  public function store(Request $request)
  {
    $this->postService->storePost($request);
    return redirect()->route('dashboard.index');
  }

  public function update(Request $request, Post $post)
  {
    $this->postService->updatePost($request, $post);
    return redirect()->route('dashboard.index');
  }

  public function destroy(Request $request)
  {
    $this->postService->deletePost($request);
    return redirect()->route('dashboard.index');
  }
}
