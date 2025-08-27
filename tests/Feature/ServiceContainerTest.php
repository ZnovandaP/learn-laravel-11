<?php

namespace Tests\Feature;

use App\Demo\Bar;
use App\Demo\Foo;
use App\Demo\HelloService;
use App\Demo\HelloServiceIndonesia;
use App\Demo\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceContainerTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testImplementationManuakDependencyInjection(): void
  {
    $foo = new Foo();
    $bar = new Bar($foo);
    self::assertEquals('Foo And Bar', $bar->fooAndBar());
  }

  public function testMakeDependencyInjectionWithServiceContainer(): void 
  {
    $foo1 = $this->app->make(Foo::class);
    $foo2 = $this->app->make(Foo::class);
    // object di atas akan selalu di instansiasi baru 

    self::assertEquals('Foo', $foo1->foo());
    self::assertEquals('Foo', $foo2->foo());
    self::assertSame($foo1, $foo2);
  }

  public function testImplementationBind(): void 
  {
    $this->app->bind(Person::class, function(){
      // return object instance dengan parameter, sehingga jika membuat object dengan make method akan menjadi instansiasi baru dengan object ini
      return new Person('Zidane', 'Novanda Putra');
    });
    
    $person1 = $this->app->make(Person::class);
    $person2 = $this->app->make(Person::class);
    // object di atas akan selalu di instansiasi baru 

    self::assertEquals('Zidane', $person1->firstname);
    self::assertEquals('Zidane', $person2->firstname);
    self::assertNotSame($person1, $person2);
  }

  public function testImplementationSingleton(): void {
    $this->app->singleton(Person::class, function(){
      return new Person('Zidane', 'Novanda Putra');
    });

    $zidane1 = $this->app->make(Person::class);
    $zidane2 = $this->app->make(Person::class);

    self::assertEquals('Zidane', $zidane1->firstname);
    self::assertEquals('Zidane', $zidane2->firstname);
    self::assertSame($zidane1, $zidane2);
  }

  public function testImplementationInstance(): void {
    $person = new Person('Zidane', 'Novanda Putra');
    $this->app->instance(Person::class, $person);

    $zidane1 = $this->app->make(Person::class);
    $zidane2 = $this->app->make(Person::class);

    self::assertEquals('Zidane', $zidane1->firstname);
    self::assertEquals('Zidane', $zidane2->firstname);
    self::assertSame($person, $zidane1);
    self::assertSame($zidane1, $zidane2);
  }
  
  public function testImplementationDependencyInjectionWithClosureAtServiceContainer(): void {
    $this->app->singleton(Foo::class);

    $this->app->singleton(Bar::class, function($app){
      // $app is the service container $app = $this->app
      return new Bar($app->make(Foo::class));
    });

    $bar1 = $this->app->make(Bar::class);
    $bar2 = $this->app->make(Bar::class);

    self::assertSame($bar1, $bar2);
  }

  public function testImplementationBindInterfaceWithClassThroughServiceContainer(): void {
    // HelloService is interface
    $this->app->singleton(HelloService::class, HelloServiceIndonesia::class);

    $helloServiceId = $this->app->make(HelloServiceIndonesia::class);

    self::assertEquals($helloServiceId->sayHello('Zidane'), 'Halo Zidane, selamat pagi!');
  }
}
