<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
  protected $fillable = ['bank_name', 'wallet_id', 'va_number', 'created_at', 'updated_at'];
  public function wallet()
  {
    return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
  }
}
