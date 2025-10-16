<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $customer = Customer::where('email', 'johndoe@example.com')->first();
    $customer->wallet()->create([
      'balance' => 1000,
    ]);
  }
}
