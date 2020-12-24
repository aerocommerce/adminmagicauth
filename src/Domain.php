<?php

namespace Aerocargo\Aeroauth;

use Illuminate\Contracts\Validation\Rule;

class Domain implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $whitelistedDomains = config('aeroauth')['whitelisted_domains'];

        $emailDomain = explode('@', $value)[1];

        if (collect($whitelistedDomains)->contains($emailDomain)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid domain.';
    }
}
