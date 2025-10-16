<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $post = Post::first();
    $post->histories()->createMany([
      [
        'action' => 'created',
        'description' => 'Post created',
        'attribute' => json_encode($post->toArray()),
        'created_by' => User::first()->id,
      ],
      [
        'action' => 'updated',
        'description' => 'Post created',
        'attribute' => json_encode($post->toArray()),
        'created_by' => User::first()->id,
      ]
    ]);

    $category = Category::first();
    $category->histories()->createMany([
      [
        'action' => 'created',
        'description' => 'Category created',
        'attribute' => json_encode($category->toArray()),
        'created_by' => User::first()->id,
      ],
      [
        'action' => 'updated',
        'description' => 'Category updated',
        'attribute' => json_encode($category->toArray()),
        'created_by' => User::first()->id,
      ]
    ]);
  }
}
