<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $customer = Customer::where('email', 'johndoe@example.com')->first();
    $wallet = $customer->wallet;
    $wallet->virtualAccount()->create([
      'bank_name' => 'BCA',
      'va_number' => '1234567890',
    ]);
  }
}
