<?php

namespace Database\Seeders;

use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = UserTest::where('username', 'test')->first();

    if (isset($user)) {
      for ($i = 0; $i < 20; $i++) {
        $user->contacts()->create([
          'firstname' => 'FirstName' . $i,
          'lastname' => 'LastName' . $i,
          'email' => 'contact' . $i . '@example.com',
          'phone' => '123-456-789' . $i,
          'user_test_id' => $user->id
        ]);
      }
    }
  }
}
