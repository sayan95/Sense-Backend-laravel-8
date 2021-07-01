<?php

namespace App\Rules\Therapist\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;
use App\Services\Interfaces\ITherapistService;

class IsCorrectPassword implements Rule
{

    private $currentUser;
    public function __construct($currentUser){
        $this->currentUser = $currentUser;
    }

    public function passes($attribute, $value)
    {
        return Hash::check($value, $this->currentUser->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your current password is incorrect';
    }
}
