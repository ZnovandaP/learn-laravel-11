<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Tag::insert([
      [
        'name' => 'Laravel',
      ],
      [
        'name' => 'PHP',
      ],
      [
        'name' => 'JavaScript',
      ],
    ]);

    $post = Post::first();
    $post->tags()->attach(Tag::pluck('id'));

    $category = Category::first();
    $category->tags()->attach(Tag::limit(1)->pluck('id'));
  }
}
