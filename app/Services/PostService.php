<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

interface PostService
{
  public function storePost(Request $request): void;
  public function updatePost(Request $request, Post $post): void;
  public function deletePost(Request $request): void;
}