<?php

namespace Tests\Feature;

use App\Demo\Bar;
use App\Demo\Foo;
use App\Demo\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testServiceProvider(): void
    {
        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);

        self::assertSame($foo, $bar->foo);
    }

    public function testSingletonsProperty(): void {
      $helloIndonesia = $this->app->make(HelloServiceIndonesia::class);

      self::assertEquals('Halo Zidane, selamat pagi!', $helloIndonesia->sayHello('Zidane'));
    }
}
