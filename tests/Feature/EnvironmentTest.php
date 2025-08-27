<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class EnvironmentTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function test_get_app_env(): void
  {
    $appEnvironment = App::environment(['testing', 'local']);
    $this->assertTrue($appEnvironment);
  }

  public function test_get_key_env(): void {
    $author = Env::get('AUTHOR', 'John Doe');
    self::assertEquals('John Doe', $author);
  }
}
