<?php
namespace App\Demo;

class HelloServiceIndonesia implements HelloService
{
  public string $name;
  public function __construct(string $name = 'guest')
  {
    $this->name = $name;
  }
  public function sayHello(string $name): string
  {
    return "Halo $name, selamat pagi!";
  }
}