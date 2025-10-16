<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::where('email', 'znovandap@example.com')->first();
    $post = Post::first();

    if ($post && $user) {
      $post->comments()->createMany([
        [
          'user_id' => $user->id,
          'body' => 'This is a comment body.',
          'rate' => 5,
        ],
        [
          'user_id' => $user->id,
          'body' => 'This is a comment body II.',
          'rate' => 3,
        ]
      ]);
    }
  }
}
