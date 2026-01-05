<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

//! DataAwareRule digunakan ketika butuh mengakses data lain dalam validasi kustom
//! ValidationRule digunakan untuk membuat aturan validasi kustom
class RegistrationRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{

  private array $data;
  private Validator $validator;

  public function setData(array $data)
  {
    $this->data = $data;
    return $this;
  }

  public function setValidator(Validator $validator)
  {
    $this->validator = $validator;
    return $this;
  }

  /**
   * Run the validation rule.
   *
   * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    if ($value === $this->data['email']) {
      $fail('The :attribute field must be different from field email.');
    }
  }
}
