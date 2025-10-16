<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::first()->image()->create([
      'url' => 'https://avatar.vercel.sh/u/123',
    ]);

    Post::first()->image()->create([
      'url' => 'https://post-image.vercel.sh/p/123',
    ]);

    /* 
    similiar to:
    $image = new Image();
    $image->url = 'https://avatar.vercel.sh/u/123';
    $image->imageable_id = User::first()->id;
    $image->imageable_type = User::class;
    $image->save();
     */
  }
}
