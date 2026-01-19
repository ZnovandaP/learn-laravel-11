<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Predis\Command\Argument\Geospatial\ByRadius;
use Predis\Command\Argument\Geospatial\FromLonLat;
use Tests\TestCase;

use function Illuminate\Log\log;

class LaravelRedisTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testConnection(): void
  {
    $this->assertEquals('PONG', Redis::ping());
    $this->assertEquals('PONG', Redis::command('PING'));
  }

  public function testRedisString(): void
  {
    Redis::setEx('data', 2, 'foo');
    $this->assertEquals('foo', Redis::get('data'));

    sleep(3);
    $this->assertNull(Redis::get('data'));

    Redis::set('data', 'foo');
    $this->assertEquals('foo', Redis::get('data'));

    Redis::del('data');
    $this->assertNull(Redis::get('data'));
  }

  public function testRedisList(): void
  {
    Redis::del('data');
    Redis::rpush('data', 'Zidane');
    Redis::rpush('data', 'Novanda');
    Redis::rpush('data', 'Putra');

    $this->assertEquals('Zidane', Redis::lindex('data', 0));
    $this->assertEquals('Novanda', Redis::lindex('data', 1));
    $this->assertEquals('Putra', Redis::lindex('data', 2));

    $this->assertEquals(['Zidane', 'Novanda', 'Putra'], Redis::lrange('data', 0, -1));
  }

  public function testRedisSet(): void
  {
    Redis::del('data');
    Redis::sadd('data', 'Zidane');
    Redis::sadd('data', 'Zidane');
    Redis::sadd('data', 'Novanda');
    Redis::sadd('data', 'Novanda');
    Redis::sadd('data', 'Putra');
    Redis::sadd('data', 'Putra');

    $this->assertEquals(3, Redis::scard('data'));
    $this->assertEquals(['Zidane', 'Novanda', 'Putra'], Redis::smembers('data'));
  }

  public function testSortedSets()
  {
    Redis::del('data');
    Redis::zadd('data', 1, 'one');
    Redis::zadd('data', 1, 'uno');
    Redis::zadd('data', 2, 'two');
    Redis::zadd('data', 3, 'three');

    $this->assertEquals(4, Redis::zcard('data'));
    $this->assertEquals(['one', 'uno', 'two', 'three'], Redis::zrange('data', 0, -1));
    $this->assertEquals([
      'one' => '1',
      'uno' => '1',
      'two' => '2',
      'three' => '3'
    ], Redis::zrange('data', 0, -1, 'withscores'));
  }

  public function testRedisHash(): void
  {
    Redis::del('data');
    Redis::hset('data', 'name', 'Novanda');
    Redis::hset('data', 'age', '20');
    Redis::hset('data', 'address', 'Jakarta');

    $this->assertEquals('Novanda', Redis::hget('data', 'name'));
    $this->assertEquals('20', Redis::hget('data', 'age'));
    $this->assertEquals('Jakarta', Redis::hget('data', 'address'));
  }

  public function testRedisGeoPoint()
  {
    Redis::del('merchant');

    Redis::geoAdd('merchant', 106.820990, -6.174704, 'toko a');
    Redis::geoAdd('merchant', 106.822696, -6.176870, 'toko b');

    $distance = Redis::geoDist('merchant', 'toko a', 'toko b', 'km');
    $this->assertEquals('0.3061', $distance);

    $searchByRadius = Redis::geoSearch('merchant', new FromLonLat(106.821666, -6.175494), new ByRadius(5, 'km'));
    $this->assertEquals(['toko b', 'toko a'], $searchByRadius);
  }

  public function testRedisHyperLogLog()
  {
    //* hyper log log: untuk menyimpan data yang sering muncul namun, tidak akan menyimpan data yang duplikasi, dan tidak dapat get all data namun bisa menghitung totalnya saja
    Redis::del('visitors');

    Redis::pfadd('visitors', 'zidane', 'ronaldo', 'mamat');
    Redis::pfadd('visitors', 'zidane', 'eko', 'dani');
    Redis::pfadd('visitors', 'tara', 'eko', 'dono');

    $total = Redis::pfcount('visitors');
    $this->assertEquals(7, $total);
  }

  public function testRedisPipeline()
  {
    // Redis pipeline untuk mengekesekusi perintah redis secara dalam satu excecution
    Redis::pipeline(function ($pipeline) {
      $pipeline->setEx('name', 2, 'zidane');
      $pipeline->setEx('address', 2, 'cimahi');
    });

    $this->assertEquals('zidane', Redis::get('name'));
    $this->assertEquals('cimahi', Redis::get('address'));
  }

  public function testRedisTransaction()
  {
    Redis::transaction(function ($transaction) {
      $transaction->setEx('name', 2, 'zidane');
      $transaction->setEx('address', 2, 'cimahi');
    });

    $this->assertEquals('zidane', Redis::get('name'));
    $this->assertEquals('cimahi', Redis::get('address'));
  }

  public function testRedisPublish()
  {
    for ($i = 1; $i <= 10; $i++) {
      Redis::publish('channel-1', "hello world $i");
      Redis::publish('channel-2', "Indonesia $i");
    }

    $this->assertTrue(true);
  }
  public function testPublishStream()
  {
    for ($i = 0; $i < 10; $i++) {
      Redis::xadd("members", "*", [
        "name" => "Eko $i",
        "address" => "Indonesia"
      ]);
    }
    self::assertTrue(true);
  }

  public function testCreateConsumer()
  {
    Redis::xgroup("create", "members", "group1", "0");
    Redis::xgroup("createconsumer", "members", "group1", "consumer-1");
    Redis::xgroup("createconsumer", "members", "group1", "consumer-2");
    self::assertTrue(true);

  }

  public function testConsumerStream()
  {
    $result = Redis::xreadgroup("group1", "consumer-1", ["members" => ">"], 3, 3000);

    self::assertNotNull($result);
    log(json_encode($result, JSON_PRETTY_PRINT));
  }

  public function testCacheWithRedis()
  {
    Cache::put('name', 'zidane', 2);
    Cache::put('address', 'cimahi', 2);

    self::assertEquals('zidane', Cache::get('name'));
    self::assertEquals('cimahi', Cache::get('address'));

    sleep(3);
    self::assertNull(Cache::get('name'));
    self::assertNull(Cache::get('address'));
  }

  public function testSessionWithRedis()
  {
    Session::put('name', 'zidane');
    Session::put('address', 'cimahi');

    self::assertEquals('zidane', Session::get('name'));
    self::assertEquals('cimahi', Session::get('address'));
  }
}