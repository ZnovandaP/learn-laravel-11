<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::where('email', 'znovandap@example.com')->first();
    $user2 = User::where('email', 'amba@example.com')->first();

    if ($user && $user2) {
      for ($i = 1; $i <= 5; $i++) {
        $user->contacts()->create([
          'name' => "Contact seed $i",
          'email' => "contact$i@localhost",
          'phone' => "0812345678$i",
          'remark' => "remark contact seed $i",
        ]);

        $user2->contacts()->create([
          'name' => "Contact2 seed $i",
          'email' => "contact$i@localhost:2",
          'phone' => "0812345678$i",
          'remark' => "remark contact2 seed $i",
        ]);
      }
    }
  }
}
