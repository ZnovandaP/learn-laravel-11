<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloControllerTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testHelloRoute(): void
  {
    $response = $this->get('/hello/zidane');

    $response->assertStatus(200);
    $response->assertSeeText('Halo zidane, selamat pagi!');
  }
}
