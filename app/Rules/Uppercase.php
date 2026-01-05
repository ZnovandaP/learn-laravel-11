<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string, ?string = null): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    if (strtoupper($value) !== $value) {
      //! message dari lang/en/validation.php | kirim message langsung ke method faild
      $fail('validation.custom.uppercase')->translate([ //! only my local code editor must have 2 parameters
        //! translate parameter akan dikirim ke message | lang/{locale}/validation.php
        'attribute' => $attribute,
        'value' => $value,
      ]);
    }
  }
}
