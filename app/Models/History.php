<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class History extends Model
{
  protected $fillable = ['action', 'description', 'created_at', 'updated_at', 'attribute', 'created_by', 'historable_id', 'historable_type'];

  public function historable(): MorphTo
  {
    return $this->morphTo();
  }
}
