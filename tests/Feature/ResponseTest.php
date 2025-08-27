<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResponseTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testResponseText(): void
  {
    $response = $this->get(route('response.text'));
    $response->assertStatus(200)
      ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
      ->assertHeader('Author', 'Zidane Novanda Putra')
      ->assertHeader('App-Name', 'Learn Laravel 11')
      ->assertSeeText('Hello, World!');
  }

  public function testJsonResponse(): void
  {
    $response = $this->get(route('response.json'));
    $response->assertStatus(200)
      ->assertHeader('Content-Type', 'application/json')
      ->assertHeader('Author', 'Zidane Novanda Putra')
      ->assertHeader('App-Name', 'Learn Laravel 11')
      ->assertJson([
        'message' => 'Hello, World!',
        'status' => 'success',
      ]);
  }

  public function testFileResponse(): void
  {
    $response = $this->get(route('response.file'));
    $response->assertStatus(200)
      ->assertHeader('Content-Type', 'image/jpeg')
      ->assertHeader('Content-Disposition', 'inline; filename="musashi.vagabond.jpeg"');
  }
}
