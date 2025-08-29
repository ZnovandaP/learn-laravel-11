<?php
namespace App\Services\Implementations;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PostServiceImpl implements PostService
{

  public function storePost(Request $request): void
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
  }

  public function updatePost(Request $request, Post $post): void
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
  }

  public function deletePost(Request $request): void
  {
    $post = Post::findOrFail($request->post_id);
    $post->delete();
    Session::flash('success', "Post: {$post->title}, deleted successfully");
  }
}