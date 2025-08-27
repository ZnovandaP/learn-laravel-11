<?php
namespace App\Demo;

class Person{
  public function __construct(public $firstname, public $lastname){
    $this->firstname = $firstname;
    $this->lastname = $lastname;
  }
}