<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Voucher::insert([
      [
        'name' => 'Voucher test',
        'voucher_code' => uuid_create(),
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ]);
  }
}
