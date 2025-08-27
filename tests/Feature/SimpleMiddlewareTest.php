<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimpleMiddlewareTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testRequestKeyIsValid(): void
  {
    $response = $this->withHeader('X-Api-Key', 'secret')->get(route('cookie.get'));

    $response->assertStatus(200);
    $response->assertJson([
      'name' => 'Guest',
    ]);
  }

  public function testRequestKeyIsInvalid(): void
  {
    $response = $this->withHeader('X-Api-Key', 'secret1')->get(route('cookie.get'));

    $response->assertStatus(401);
    $response->assertJson([
      'status' => 401,
      'message' => 'Unauthorized',
    ]);
  }
}
