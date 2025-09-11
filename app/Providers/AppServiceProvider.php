<?php

namespace App\Providers;

use App\Demo\HelloService;
use App\Demo\HelloServiceIndonesia;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();
    Blade::directive('datetime', function ($expression) {
      return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
    });

    Blade::stringable(HelloServiceIndonesia::class, function (HelloServiceIndonesia $service) {
      return $service->name . ', ' . $service->sayHello('Novanda');
    });
  }
}
