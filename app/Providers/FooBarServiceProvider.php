<?php

namespace App\Providers;

use App\Demo\Bar;
use App\Demo\Foo;
use App\Demo\HelloService;
use App\Demo\HelloServiceIndonesia;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FooBarServiceProvider extends ServiceProvider implements DeferrableProvider
{
  public array $singletons = [
    HelloService::class => HelloServiceIndonesia::class
  ];

  /**
   * Register services.
   */
  public function register(): void
  {
    // echo 'register';
    $this->app->singleton(Foo::class, function () {
      return new Foo();
    });

    $this->app->singleton(Bar::class, function (Application $app) {
      return new Bar($app->make(Foo::class));
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }

  // deferred provider
  public function provides()
  {
    return [
      HelloService::class,
      Bar::class,
      Foo::class,
      HelloServiceIndonesia::class
    ];
  }
}
