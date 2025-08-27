<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequestTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testRequestHello(): void
  {
    $response = $this->get('/hello/info/request', [
      'Accept' => 'plain/text',
    ])->assertStatus(200)
      ->assertSeeText('hello/info/request')
      ->assertSeeText('http://localhost/hello/info/request')
      ->assertSeeText('GET')
      ->assertSeeText('plain/text');

  }
}
