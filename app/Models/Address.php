<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;

  protected $fillable = [
    'street',
    'city',
    'state',
    'country',
    'province',
    'postal_code',
    'contact_id',
    'created_at',
    'updated_at',
  ];

  public function contact()
  {
    return $this->belongsTo(Contact::class);
  }
}
