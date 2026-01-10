<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserTestRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Service\UserTestService;

class UserTestController extends Controller
{
  private UserTestService $userTestService;

  public function __construct(UserTestService $userTestService)
  {
    $this->userTestService = $userTestService;
  }
  public function registerUser(RegisterUserTestRequest $request)
  {
    $data = $request->validated();
    return $this->userTestService->registerUser($data);
  }

  public function loginUser(LoginRequest $request)
  {
    $data = $request->validated();
    return $this->userTestService->loginUser($data);
  }

  public function getCurrentUser()
  {
    return $this->userTestService->getCurrentUser();
  }

  public function updateUser(UpdateUserRequest $request)
  {
    $data = $request->validated();
    return $this->userTestService->updateUser($data);
  }

  public function logoutUser()
  {
    return $this->userTestService->logoutUser();
  }
}
