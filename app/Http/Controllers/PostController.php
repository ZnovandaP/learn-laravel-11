<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
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
        $title = 'Dashboard Managament Post';

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
        Session::put('temp', [
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
            'author' => $request->author,
        ]);

        $form = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'body' => 'required|string|min:10',
            'category' => 'required|exists:categories,id',
            'author' => 'required|exists:users,id',
        ]);

        $slug = Str::slug(strtolower($form['title']));

        $post = new Post();
        $post->title = $form['title'];
        $post->slug = $slug;
        $post->body = $form['body'];
        $post->category_id = $form['category'];
        $post->author_id = $form['author'];

        $post->save();

        Session::flash('success', 'Post created successfully');
        Session::forget('temp');

        return redirect()->route('dashboard.index');
    }

    public function update(Request $request, Post $post)
    {
        $form = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'body' => 'required|string|min:10',
            'category' => 'required|exists:categories,id',
            'author' => 'required|exists:users,id',
        ]);

        $slug = Str::slug(strtolower($form['title']));

        $post->title = $form['title'];
        $post->slug = $slug;
        $post->body = $form['body'];
        $post->category_id = $form['category'];
        $post->author_id = $form['author'];
        $post->updated_at = now();

        $post->save();

        Session::flash('success', "Post: {$post->title}, updated successfully");
        return redirect()->route('dashboard.index');
    }

    public function destroy(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $post->delete();
        Session::flash('success', "Post: {$post->title}, deleted successfully");
        return redirect()->route('dashboard.index');
    }
}
