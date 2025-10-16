<?php

namespace Tests\Feature;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DbFacadeLearningTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    DB::delete('delete from test_categories');
  }
  /**
   * A basic feature test example.
   */
  public function testInsertDataSuccessWithDbTransaction(): void
  {
    try {
      DB::transaction(function () {
        DB::insert('insert into test_categories(id, name, description, created_at) values (?, ?, ?, ?)', [
          'GADGET',
          'Gadget Mobile',
          'Test Description',
          now()
        ]);

        DB::insert('insert into test_categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
          'id' => 'FASHION',
          'name' => 'Classic Fashion',
          'description' => 'Test Description',
          'created_at' => now()
        ]);
      });
    } catch (QueryException $th) {
      //throw $th;
    }

    $categories = DB::select('select * from test_categories order by name');
    $this->assertCount(2, $categories);
    $this->assertEquals('Classic Fashion', $categories[0]->name);
    $this->assertEquals('Gadget Mobile', $categories[1]->name);
  }

  public function testInsertDataFailedWithDbTransaction(): void
  {
    try {
      DB::beginTransaction();
      DB::insert('insert into test_categories(id, name, description, created_at) values (?, ?, ?, ?)', [
        'GADGET',
        'Gadget Mobile',
        'Test Description',
        now()
      ]);

      DB::insert('insert into test_categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
        'id' => 'GADGET',
        'name' => 'Gadget Desktop',
        'description' => 'Test Description',
        'created_at' => now()
      ]);
      DB::commit();
    } catch (QueryException $th) {
      DB::rollBack();
    }

    $categories = DB::select('select * from test_categories order by name');
    $this->assertCount(0, $categories);
  }

  public function testUseQueryBuilder(): void
  {
    try {
      DB::table('test_categories')->insert([
        'id' => 'GADGET',
        'name' => 'Gadget Mobile',
        'description' => 'Test Description',
        'created_at' => now()
      ]);

      DB::table('test_categories')->insert([
        'id' => 'FASHION',
        'name' => 'Classic Fashion',
        'description' => 'Test Description',
        'created_at' => now()
      ]);
      DB::table('test_categories')->insert([
        'id' => 'LAPTOP',
        'name' => 'Laptop Asus',
        'description' => 'Test Description',
        'created_at' => now()
      ]);

      DB::table('test_categories')->insert([
        'id' => 'CHAIR',
        'name' => 'Classic Chair',
        'description' => 'Test Description',
        'created_at' => now()
      ]);
    } catch (QueryException $th) {
      //throw $th;
    }

    $categories = DB::table('test_categories')->select(['id', 'name'])->get();
    $this->assertCount(4, $categories);

    // test where
    $categories = DB::table('test_categories')->where(function (Builder $builder) {
      $builder->where('id', 'GADGET');
      $builder->orWhere('id', 'FASHION');
    })->get();
    self::assertCount(2, $categories);
  }

  public function testDbLockForUpdate()
  {
    DB::table('test_categories')->insert([
      'id' => 'GADGET',
      'name' => 'Gadget Mobile',
      'description' => 'Test Description',
      'created_at' => now()
    ]);

    DB::transaction(function () {
      $category = DB::table('test_categories')->where('id', 'GADGET')->lockForUpdate()->first();
      $this->assertNotNull($category);
      $this->assertEquals('Gadget Mobile', $category->name);
      sleep(10);
      DB::table('test_categories')->where('id', 'GADGET')->update([
        'name' => 'Gadget Mobile Updated',
        'updated_at' => now()
      ]);
    });

    $category = DB::table('test_categories')->where('id', 'GADGET')->first();
    $this->assertNotNull($category);
    $this->assertEquals('Gadget Mobile Updated', $category->name);
  }
}
