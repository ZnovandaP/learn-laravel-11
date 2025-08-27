<?php
namespace App\Demo;

class Bar
{
  public $foo;

  public function __construct(Foo $foo)
  {
    $this->foo = $foo;
  }

  function fooAndBar(): string
  {
    return $this->foo->foo() . ' And Bar';
  }
}