<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
  use HasFactory;
  protected $fillable = ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at'];
  protected $keyType = 'string';

  //* global scope, akan dipanggil secara implisit di setiap query pada model ini
  static protected function booted()
  {
    parent::addGlobalScope(new IsActiveScope());
  }
}
