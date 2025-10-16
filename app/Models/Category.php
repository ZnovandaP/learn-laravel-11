<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'slug'];

  public function posts(): HasMany
  {
    return $this->hasMany(Post::class);
  }

  //* has one of many relation
  public function newestPost(): HasOne
  {
    return $this->hasOne(Post::class, 'category_id', 'id')->latest('created_at');
  }

  public function comments(): HasManyThrough
  {
    return $this->hasManyThrough(
      Comment::class, // model yang dituju
      Post::class, // model perantara
      'category_id', // FK di model perantara
      'post_id', // FK di model yang dituju
      'id', // PK di model saat ini
      'id' // PK di model perantara
    );
  }

  public function histories(): MorphMany
  {
    return $this->morphMany(History::class, 'historable');
  }

  public function tags(): MorphToMany
  {
    return $this->morphToMany(Tag::class, 'taggable');
  }
}
