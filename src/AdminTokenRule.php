<?php

namespace Aerocargo\Aeroauth;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AdminTokenRule implements Rule
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
        $adminToken = AdminToken::where(['token' => request()->input('token')])->first();


        if (!$adminToken) {
            return false;
        }

        if ($adminToken->expires_at <= Carbon::now()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has expired. Please try again.';
    }
}
