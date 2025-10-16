<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
  use HasFactory;
  protected $fillable = ["title", "slug", "author", "Category_id", "body"];

  //! eager loading by default
  protected $with = ["author", "category"];

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, "author_id");
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class, 'post_id', 'id');
  }

  public function likedByUsers(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'user_like_posts', 'post_id', 'user_id')
      ->using(LikePost::class)->withPivot(['created_at', 'updated_at']);
  }

  public function image(): MorphOne
  {
    return $this->morphOne(Image::class, 'imageable');
  }

  // one to many polymorphic
  public function histories(): MorphMany
  {
    return $this->morphMany(History::class, 'historable');
  }

  public function latestHistory(): MorphOne
  {
    return $this->morphOne(History::class, 'historable')->latest('created_at');
  }

  public function tags(): MorphToMany
  {
    return $this->morphToMany(Tag::class, 'taggable');
  }

  public function scopeFilter(Builder $query, array $filters): void
  {
    $query->when(
      $filters["title"] ?? false,
      fn($query, $title) => $query->where('title', 'like', "%$title%")
    );

    $query->when(
      $filters["category"] ?? false,
      fn($query, $category) =>
      $query->whereHas(
        'category',
        fn($query) => $query->where('slug', $category)
      )
    );

    //! search paramas by author's username
    $query->when(
      $filters["author"] ?? false,
      fn($query, $author) =>
      $query->whereHas(
        'author',
        fn($query) => $query->where('username', $author)
      )
    );
  }
}
