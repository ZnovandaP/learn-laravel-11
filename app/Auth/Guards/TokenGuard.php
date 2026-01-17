<?php
namespace App\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class TokenGuard implements Guard
{
  use GuardHelpers;

  public function __construct(UserProvider $provider, protected Request $request)
  {
    $this->provider = $provider;
    $this->request = $request;
  }

  public function user(): Authenticatable|null
  {
    if ($this->user !== null) {
      return $this->user;
    }

    $token = $this->request->bearerToken();

    if ($token) {
      $user = $this->provider->retrieveByCredentials(['token' => $token]);
      if ($user) {
        $this->setUser($user);
      }
    }
    return $this->user;
  }

  public function validate(array $credentials = []): bool
  {
    return $this->provider->validateCredentials($this->user, $credentials);
  }
}