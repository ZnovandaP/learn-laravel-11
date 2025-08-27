<?php

namespace App\Http\Controllers;

use App\Demo\HelloService;
use App\Demo\HelloServiceIndonesia;
use Illuminate\Http\Request;

class HelloController extends Controller
{
  public function __construct(private HelloServiceIndonesia $helloServiceIndonesia)
  {
    $this->helloServiceIndonesia = $helloServiceIndonesia;
  }
  public function hallo(string $name)
  {
    return $this->helloServiceIndonesia->sayHello($name);
  }

  public function request(Request $request)
  {
    return $request->path() . PHP_EOL .
      $request->url() . PHP_EOL .
      $request->fullUrl() . PHP_EOL .
      $request->ip() . PHP_EOL .
      $request->method() . PHP_EOL .
      $request->header('Accept') . PHP_EOL;

  }

  public function input(Request $request)
  {
    return $request->input('name', 'default');
  }
}
