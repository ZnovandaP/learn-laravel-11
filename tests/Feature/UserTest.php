<?php

namespace Tests\Feature;

use Database\Seeders\UserTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotEquals;

class UserTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    DB::delete('delete from user_tests');
  }
  public function testSuccessRegisterUser(): void
  {
    $response = $this->post('/api/user-test/register', [
      'username' => 'testuser',
      'password' => 'Password123!',
      'name' => 'Test User',
    ]);

    $response->assertStatus(201);
    $response->assertjson([
      "data" => [
        'username' => 'testuser',
        'name' => 'Test User'
      ]
    ]);
  }

  public function testFailedRegistrationValidation(): void
  {
    $response = $this->post('/api/user-test/register', [
      'username' => '',
      'password' => '',
      'name' => '',
    ]);

    $response->assertStatus(400);
    $response->assertJsonStructure([
      'errors' => [
        'username',
        'password',
        'name'
      ]
    ]);
  }

  public function testFailedRegistrationUsernameDuplicated(): void
  {
    $this->seed(UserTestSeeder::class);

    $response = $this->post('/api/user-test/register', [
      'username' => 'test',
      'password' => 'Password123!',
      'name' => 'Test User',
    ]);

    $response->assertStatus(400);
    $response->assertJson([
      'errors' => [
        'username' => [
          'The username has already been taken.'
        ]
      ]
    ]);
  }


  public function testSuccessLoginUser(): void
  {
    $this->seed(UserTestSeeder::class);

    $response = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
      'data' => [
        'id',
        'username',
        'name',
        'token',
      ]
    ]);

    $userAuth = Auth::user();
    $this->assertNotNull($userAuth);
    $this->assertEquals('test', $userAuth->username);
  }

  public function testFailedLoginInvalidCredentials(): void
  {
    $this->seed(UserTestSeeder::class);

    $response = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'WrongPassword12!',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Username or Password is invalid credentials!'
    ]);
  }

  public function testGetCurrentUser(): void
  {
    $this->seed(UserTestSeeder::class);

    $loginResponse = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);

    $token = $loginResponse->json('data.token');

    $response = $this->withHeaders([
      'Authorization' => $token,
    ])->get('/api/user-test/me');

    $response->assertStatus(200);
    $response->assertJson([
      'data' => [
        'username' => 'test',
        'name' => 'test',
      ]
    ]);
  }

  public function testGetCurrentUserUnauthorized(): void
  {
    $response = $this->get('/api/user-test/me');

    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing',
    ]);
  }

  public function testUpdateUser(): void
  {
    $this->seed(UserTestSeeder::class);

    $loginResponse = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);

    $token = $loginResponse->json('data.token');

    $response = $this->withHeaders([
      'Authorization' => $token,
    ])->patch('/api/user-test/update', [
          'name' => 'Updated Name',
          'password' => 'NewPassword123!'
        ]);

    $response->assertStatus(200);
    $response->assertJson([
      'data' => [
        'username' => 'test',
        'name' => 'Updated Name',
      ]
    ]);

    $this->assertNotEquals('Updated Name', 'test');
  }

  public function testUpdateUserUnauthorized(): void
  {
    $response = $this->patch('/api/user-test/update', [
      'name' => 'Updated Name',
      'password' => 'NewPassword123!'
    ]);

    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing',
    ]);
  }

  public function testUpdateUserValidationFailed(): void
  {
    $this->seed(UserTestSeeder::class);

    $loginResponse = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);

    $token = $loginResponse->json('data.token');

    $response = $this->withHeaders([
      'Authorization' => $token,
    ])->patch('/api/user-test/update', [
          'name' => Str::random(101),
          'password' => 'NewPassword'
        ]);

    $response->assertStatus(400);
    $response->assertJsonStructure([
      'errors' => [
        'name',
        'password'
      ]
    ]);
  }

  public function testLogoutUser(): void
  {
    $this->seed(UserTestSeeder::class);

    $loginResponse = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);

    $token = $loginResponse->json('data.token');

    $response = $this->withHeaders([
      'Authorization' => $token,
    ])->delete('/api/user-test/logout');

    $response->assertStatus(200);
    $response->assertJson([
      'message' => 'Successfully logged out',
      'data' => true
    ]);
    $this->assertNull(Auth::user());
  }

  public function testLogoutUserUnauthorized(): void
  {
    $response = $this->delete('/api/user-test/logout');

    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing',
    ]);
  }
}