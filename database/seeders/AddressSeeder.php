<?php

namespace Database\Seeders;

use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = UserTest::where('username', 'test')->first();

    if ($user) {
      $contact = $user->contacts()->first();
      if ($contact) {
        for ($i = 1; $i <= 5; $i++) {
          $contact->addresses()->create([
            'street' => "Test Street $i",
            'city' => "Test City $i",
            'province' => "Test Province $i",
            'postal_code' => "12345",
            'country' => "Test Country $i",
          ]);
        }
      }
    }
  }
}
