<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class LearnLaravelSecurityTest extends TestCase
{

  private string $emailUser1 = 'znovandap@example.com';
  private string $emailUser2 = 'amba@example.com';

  protected function setUp(): void
  {
    parent::setUp();
  }

  public function testAccessPathWithTokenGuard(): void
  {
    $response = $this->post('/users/login', [
      'email' => $this->emailUser1,
      'password' => 'password'
    ]);
    $response->assertStatus(200);
    $token = $response->json('token');


    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . $token
    ])->get('/users/current');
    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        'user' => [
          'id',
          'name',
          'username',
          'email',
          'email_verified_at',
          'created_at',
          'updated_at',
        ]
      ]
    ]);

    $id = $response->json('data.user.id');
    $this->assertEquals($token, User::where('id', $id)->first()->token);
  }

  public function testAccessPathWithInvalidTokenGuard(): void
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . 'invalid-token',
    ])->get('/users/current');
    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Unauthenticated.',
      'code' => 401
    ]);
  }

  public function testAccessPathWithSimpleTokenGuardWithSimpleUserTestProvider(): void
  {
    $response = $this->withHeaders([
      'Authorization' => 'Bearer ' . 'rahasia',
    ])->get('/users/simple-current'); // * with simple-token guard & simple user provider
    $response->assertStatus(200);
    $response->assertJson([
      'data' => [
        'user' => []
      ]
    ]);
  }

  public function testGateFeature()
  {
    $user = User::where('email', $this->emailUser1)->first();
    $contact = $user->contacts()->first();

    $this->assertTrue($user->can('get-contact', $contact));
    $this->assertTrue($user->can('update-contact', $contact));
    $this->assertTrue($user->can('delete-contact', $contact));

    Auth::login($user);
    $this->assertTrue(Gate::allows('get-contact', $contact));
    $this->assertTrue(Gate::allows('update-contact', $contact));
    $this->assertTrue(Gate::allows('delete-contact', $contact));
  }

  public function testPolicyFeature(): void
  {
    $user = User::where('email', $this->emailUser1)->first();
    $contact = $user->contacts()->first();

    $this->assertTrue($user->can('view', $contact));
    $this->assertTrue($user->can('update', $contact));
    $this->assertTrue($user->can('delete', $contact));

    Auth::login($user);
    $this->assertTrue(Gate::allows('view', $contact));
    $this->assertTrue(Gate::allows('update', $contact));
    $this->assertTrue(Gate::allows('delete', $contact));
  }
}