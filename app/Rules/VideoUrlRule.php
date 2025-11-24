<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VideoUrlRule implements ValidationRule {
    /**
    * Run the validation rule.
    *
    * @param  \Closure( string, ?string = ): \Illuminate\Translation\PotentiallyTranslatedString  $fail
    */

    public function validate( string $attribute, mixed $value, Closure $fail ): void {
        $patterns = [
            'vimeo' => '/^(https?:\/\/)?(www\.)?vimeo\.com\/.+/',
            'loom' => '/^(https?:\/\/)?(www\.)?loom\.com\/share\/.+/'
        ];

        // Check if the value matches ANY of the patterns (OR logic)
        foreach($patterns as $platform => $pattern){
            if(preg_match($pattern, $value)){
                return; // Valid URL found, no need to fail
            }
        }

        // None of the patterns matched, fail validation
        $fail('The :attribute must be a valid Vimeo or Loom video URL.');
    }
}
