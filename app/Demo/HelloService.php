<?php
namespace App\Demo;

interface HelloService
{
  public function sayHello(string $name): string;
}