<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\ITherapistService;
use App\Traits\Therapist\ConfirmPasswordTrait;
use Carbon\Carbon;

class ConfirmPasswordController extends Controller
{   
    use ConfirmPasswordTrait;
    private $therapistService;

    public function __construct(ITherapistService $therapistService){
        $this->therapistService = $therapistService;
    }

    /**
     * confirms and reset the old password (for unauthenticated user)
     */
    public function confirmPasswordChange(Request $request, $token){
        // check for valid url signature
        if(!URL::hasValidSignature($request)){
            return $this->sendInvalidRouteSignatureResponse();
        }
        
        // validates user request
        $this->validator($request->all())->validate();

        // check if the request is valid in terms of token
        $email = urldecode($request->email);
        if($this->therapistService->validatePasswordResetToken($email, $token)){
            $this->therapistService->deregisterForPasswordReset($email);
        }else{
            return $this->denyPasswordChangeResponse();
        }

        // updates the password
        $therapist = $this->therapistService->findTherapistBySpecificField('email', $email, []);
        $this->therapistService->updateTherapistDetails($therapist->id, [
            'password' => bcrypt($request->password),
            'password_reset_initiated' => true,
            'password_reset_at' => Carbon::now()
        ]);

        // return password change success response
        return $this->sendPassswordChangeSuccessResponse();   
    }
}
