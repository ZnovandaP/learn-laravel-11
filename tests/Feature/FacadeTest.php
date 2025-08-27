<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FacadeTest extends TestCase
{
  public function testFacade(): void
  {
    $firstname1 = config('contoh.author.first_name');
    $firstname2 = Config::get('contoh.author.first_name');

    var_dump($firstname1);
    self::assertEquals($firstname1, $firstname2);
  }

  public function testConfigDependency()
  {
    $config = $this->app->make('config');
    $firsname1 = $config->get('contoh.author.first_name');
    $firsname2 = Config::get('contoh.author.first_name');
    $firsname3 = config()->get('contoh.author.first_name');

    self::assertEquals($firsname1, $firsname2);
    self::assertEquals($firsname1, $firsname3);
  }

  public function testConfigMock()
  {
    // Facade
    Config::shouldReceive('get')->once()->with('contoh.author.first_name')->andReturn('John');
    $firstname2 = Config::get('contoh.author.first_name');

    self::assertEquals('John', $firstname2);
  }
}


