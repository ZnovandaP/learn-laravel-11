<?php

namespace App\Exceptions;

use Exception;

class UserInactiveException extends Exception
{
  private $userId;
  public function __construct(int $userId, string $message = 'User is inactive')
  {
    parent::__construct($message, 403);
    $this->userId = $userId;
  }

  public function getUserId()
  {
    return $this->userId;
  }
}
