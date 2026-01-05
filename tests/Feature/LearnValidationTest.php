<?php

namespace Tests\Feature;

use App\Rules\RegistrationRule;
use App\Rules\Uppercase;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class LearnValidationTest extends TestCase
{
  public function testValidationWithFacade()
  {
    $data = [
      'name' => 'Zidane',
      'email' => 'H3YQm@example.com',
    ];

    $rules = [
      'name' => 'required|min:5|max:10',
      'email' => 'required|email',
    ];

    $validator = Validator::make($data, $rules);

    self::assertFalse($validator->fails());
    self::assertTrue($validator->passes());
  }

  public function testValidationFailedWithFacade()
  {
    $data = [
      'name' => 'Zid',
      'email' => 'H3YQmexample.com',
    ];

    $rules = [
      'name' => 'required|min:5|max:10',
      'email' => 'required|email',
    ];

    // custom messages 
    $messages = [
      'name.min' => 'The name must be at least :min characters.',
      'email.email' => 'The email must be a valid email address.',
    ];

    $validator = Validator::make($data, $rules, $messages);

    self::assertTrue($validator->fails());
    self::assertFalse($validator->passes());

    $errors = $validator->errors(); // get messages | Error Message Bag

    self::assertEquals('The name must be at least 5 characters.', $errors->first('name'));
    self::assertEquals('The email must be a valid email address.', $errors->first('email'));
  }

  public function testValidationExceptionWithFacade() //with valdiation facedes
  {
    $data = [
      'name' => 'Zid',
      'email' => 'H3YQmexample.com',
      'password' => 'H3YQmexample.com',
      'username' => 'ZIDANE67',
    ];

    $rules = [
      'name' => ['required', 'min:5', 'max:10', new Uppercase()], // memanggil rule kustom (class based)
      'email' => ['required', 'email'],
      'password' => ['required', 'min:8', new RegistrationRule()], // memanggil rule kustom yang butuh data lain
      'username' => [ // custom rule (function based )
        'required',
        function (string $attribute, string $value, Closure $fail) {
          if (strtolower($value) !== $value) {
            $fail("The $attribute field with value $value must not be lowercase.");
          }
        }
      ],
    ];

    $messages = [
      'name.min' => 'The name must be at least :min characters.',
      'email.email' => 'The email must be a valid email address.',
    ];
    $validator = Validator::make($data, $rules, $messages);

    try {
      $validator->validate();
    } catch (\Illuminate\Validation\ValidationException $e) {
      $errors = $e->errors();
      self::assertEquals('The name must be at least 5 characters.', $errors['name'][0]);
      self::assertEquals('The name field with value Zid must be UPPERCASE letters only.', $errors['name'][1]);
      self::assertEquals('The email must be a valid email address.', $errors['email'][0]);
      self::assertEquals('The password field must be different from field email.', $errors['password'][0]);
      self::assertEquals('The username field with value ZIDANE67 must not be lowercase.', $errors['username'][0]);
    }
  }

  public function testValidationWithRuleClass()
  {
    $data = [
      'username' => 'Zidane',
      'password' => 'xoxo1234#',
    ];

    $rules = [
      'username' => 'required|min:5|max:10',
      'password' => ['required', Password::min(8)->letters()->numbers()->symbols()], // menggunakan rule class bawaan laravel [Pssword Rule Class]
    ];

    $validator = Validator::make($data, $rules);

    self::assertFalse($validator->fails());
    self::assertTrue($validator->passes());
  }

  public function testValidationNestedArrayAssoc()
  {
    $data1 = [
      'products' => [
        [
          'name' => 'Product 1',
          'price' => 1000,
        ],
        [
          'name' => 'Product 2',
          'price' => 2000,
        ],
      ],
    ];

    // indexed array (multiple data) -> gunakan *
    $rules1 = [
      'products.*.name' => 'required|string',
      'products.*.price' => 'required|integer|min:100',
    ];

    $validator = Validator::make($data1, $rules1);

    self::assertFalse($validator->fails());
    self::assertTrue($validator->passes());
  }
}