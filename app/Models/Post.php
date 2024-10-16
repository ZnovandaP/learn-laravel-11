<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
