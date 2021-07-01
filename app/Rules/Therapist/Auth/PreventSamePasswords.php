<?php

namespace App\Rules\Therapist\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class PreventSamePasswords implements Rule
{

    private $currentPassword;
    public function __construct($currentPassword){
        $this->currentPassword = $currentPassword;
    }

    public function passes($attribute, $value)
    {
        return !Hash::check($this->currentPassword, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your new password should be different from current password';
    }
}
