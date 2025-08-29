<?php

namespace App\Providers;

use App\Services\Implementations\PostServiceImpl;
use App\Services\PostService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider implements DeferrableProvider
{
  /**
   * Register services.
   */

  public $singletons = [
    PostService::class => PostServiceImpl::class
  ];

  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
