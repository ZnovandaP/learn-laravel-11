<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $fillable = ['name', 'email', 'created_at', 'updated_at'];
  public function wallet()
  {
    return $this->hasOne(Wallet::class, 'customer_id', 'id');
  }

  public function virtualAccount()
  {
    return $this->hasOneThrough(
      VirtualAccount::class, // model yang dituju
      Wallet::class, // model perantara
      'customer_id', // FK di model perantara
      'wallet_id', // FK di model yang dituju
      'id', // PK di model saat ini
      'id' // PK di model perantara
    );
  }
}
