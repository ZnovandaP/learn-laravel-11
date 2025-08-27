<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CookieTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testSetCookie(): void
  {
    $response = $this->withHeader('X-Api-Key', 'secret')->get(route('cookie.set'));

    $response
      ->assertStatus(200)
      ->assertSeeText('Cookie set')
      ->assertCookie('name', 'John Doe');
  }

  public function testGetCookie(): void
  {
    $response = $this->withHeader('X-Api-Key', 'secret')->withCookie('name', 'John Doe')->get(route('cookie.get'));

    $response
      ->assertStatus(200)
      ->assertJson([
        'name' => 'John Doe',
      ]);
  }

  public function testDeleteCookie(): void
  {
    $response = $this->withHeader('X-Api-Key', 'secret')->withCookie('name', 'John Doe')->get(route('cookie.delete'));

    $response
      ->assertStatus(200)
      ->assertSeeText('Cookie deleted')
      ->assertCookieExpired('name');
  }
}

