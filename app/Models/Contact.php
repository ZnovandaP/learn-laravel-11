<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  use HasFactory;

  protected $fillable = [
    'firstname',
    'lastname',
    'email',
    'phone',
    'user_test_id',
    'created_at',
    'updated_at',
  ];

  public function userTest()
  {
    return $this->belongsTo(UserTest::class);
  }

  public function addresses()
  {
    return $this->hasMany(Address::class);
  }
}
