<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
  use HasFactory, HasUuids, SoftDeletes;

  protected $fillable = ['name', 'voucher_code', 'created_at', 'updated_at'];

  protected $increment = false;
  protected $keyType = 'string';

  protected $attributes = [
    'name' => 'default value voucher name',
  ];

  public function uniqueIds()
  {
    return [$this->primaryKey, 'voucher_code'];
  }

}
