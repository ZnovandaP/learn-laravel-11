<?php
namespace App\Service;

use App\Http\Resources\UserTestResource;
use App\Models\UserTest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTestService
{
  public function registerUser(array $data): JsonResponse
  {
    try {
      return DB::transaction(function () use ($data) {
        $user = UserTest::create([
          'username' => $data['username'],
          'password' => Hash::make($data['password']),
          'name' => $data['name'],
        ]);

        return (new UserTestResource($user))->response()->setStatusCode(201);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function loginUser(array $data): UserTestResource
  {
    try {
      $user = UserTest::where('username', $data['username'])->first();

      if (!$user || !Hash::check($data['password'], $user->password)) {
        throw new HttpResponseException(response(
          [
            "message" => "Username or Password is invalid credentials!"
          ],
          401
        ));
      }

      $user->token = Str::uuid()->toString();
      $user->save();
      Auth::login($user);
      return new UserTestResource($user);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getCurrentUser()
  {
    return new UserTestResource(Auth::user());
  }

  public function updateUser(array $data)
  {
    try {
      $user = Auth::user();
      $user->name = isset($data['name']) ? $data['name'] : $user->name;
      $user->password = isset($data['password']) ? Hash::make($data['password']) : $user->password;
      $user->save();
      return new UserTestResource($user);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function logoutUser()
  {
    try {
      $user = Auth::user();
      $user->token = null;
      $user->save();
      Auth::logout();
      return response()->json([
        'message' => 'Successfully logged out',
        'data' => true
      ], 200);
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}