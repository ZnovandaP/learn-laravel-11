<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

  protected $fillable = ['customer_id', 'balance', 'created_at', 'updated_at'];
  public function customer()
  {
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
  }

  public function virtualAccount()
  {
    return $this->hasOne(VirtualAccount::class, 'wallet_id', 'id');
  }
}
