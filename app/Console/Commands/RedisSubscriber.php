<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscriber extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'redis.subs';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Redis Subscirber';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('Subscribe to channels: Listening...' . PHP_EOL);
    Redis::subscribe(['channel-1', 'channel-2'], function ($message) {
      $this->info("Received message: $message");
    });
  }
}
