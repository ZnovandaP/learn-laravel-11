<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LikePost extends Pivot
{
  protected $table = 'user_like_posts';
  protected $primaryKey = 'id';
  // protected $foreignKey = 'user_id';
  // protected $relatedKey = 'post_id';
  protected $fillable = ['user_id', 'post_id', 'created_at', 'updated_at'];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function post()
  {
    return $this->belongsTo(Post::class, 'post_id', 'id');
  }
}
