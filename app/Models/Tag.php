<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $fillable = ['name', 'created_at', 'updated_at'];

  public function posts()
  {
    return $this->morphedByMany(Post::class, 'taggable');
  }

  public function categories()
  {
    return $this->morphedByMany(Category::class, 'taggable');
  }
}
