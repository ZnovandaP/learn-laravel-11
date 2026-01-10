<?php

namespace Database\Seeders;

use App\Models\UserTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    UserTest::create([
      'username' => 'test',
      'password' => Hash::make('Qwerty123!'),
      'name' => 'test',
      'token' => 'test'
    ]);

    UserTest::create([
      'username' => 'test2',
      'password' => Hash::make('Qwerty123!'),
      'name' => 'test2',
      'token' => 'test2'
    ]);
  }
}
