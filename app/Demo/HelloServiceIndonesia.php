<?php
namespace App\Demo;

class HelloServiceIndonesia implements HelloService {
  public function sayHello(string $name): string {
    return "Halo $name, selamat pagi!";
  }
}