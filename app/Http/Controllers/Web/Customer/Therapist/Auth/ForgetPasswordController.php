<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Therapist\ForgetPasswordTrait;
use App\Services\Interfaces\ITherapistService;

class ForgetPasswordController extends Controller
{
    use ForgetPasswordTrait;
    private $therapistService;

    public function __construct(ITherapistService $therapistService){   
        $this->therapistService = $therapistService;
        $this->middleware('throttle:6, 1');
    }

    /**
     * sends password reset link to the email
     */
    public function sendPasswordResetLink(Request $request){
        $this->valiadator($request->all())->validate();

        // check if email is present in database or not
        $therapist = $this->therapistService->findTherapistBySpecificField('email', $request->email, []);
        if(!$therapist){
            return $this->sendEmailInavlidResponse();
        }

        // create a random string token
        $token = Str::random(20);

        // register the email for password reset
        $this->therapistService->registerTherapistForPasswordReset($request->email, $token);

        // sends password reset link to user
        $therapist->sendPasswordResetMail($token);
        
        // send reset link send reposne
        return $this->sendResetLinkSentResponse();
    }   
}
