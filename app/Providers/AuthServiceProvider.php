<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\Guards\TokenGuard;
use App\Models\Contact;
use App\Models\User;
use App\Policies\ContactPolicy;
use App\Providers\User\SimpleUserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    Contact::class => ContactPolicy::class,
  ];
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    Auth::extend('token', function (Application $app, string $name, array $config) {
      return new TokenGuard(
        Auth::createUserProvider($config['provider']),
        $app->make('request')
      );
    });

    Auth::provider('simple', function ($app, array $config) {
      return new SimpleUserProvider();
    });

    Gate::define('get-contact', function (User $user, Contact $contact) {
      return $user->id === $contact->user_id;
    });

    Gate::define('update-contact', function (User $user, Contact $contact) {
      return $user->id === $contact->user_id;
    });

    Gate::define('delete-contact', function (User $user, Contact $contact) {
      return $user->id === $contact->user_id;
    });

    Gate::before(function (User $user, string $ability) {
      if ($user->is_admin) {
        return true;
      }
    });

  }
}
