<?php

namespace App\Http\Controllers\Web\Customer\therapist\Auth;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Services\Interfaces\ITherapistService;
use App\Traits\Therapist\EmailVerificationTrait;

class ValidateRegisterOTPController extends Controller
{
    use EmailVerificationTrait;

    private $therapistService;
    
    public function __construct(ITherapistService $therapistService)
    {
        $this->therapistService = $therapistService;
    }

    /**
     * verifying account activation request
     */
    public function verify(Request $request)
    {
        // get the entered otp
        $otp_from_client = $request->otp;
        
        // get the otp from cookie                     
        $otp_from_cookie = $request->Cookie('OTP_COOKIE')?(string)Crypt::decrypt($request->cookie('OTP_COOKIE')): '';

        //  get the attempted user
        if($request->cookie('attempter'))
            cookie()->forget('attempter');
        
        $user_from_cookie = $request->cookie('attempter');
         
        // return response for invalid attempter
        if(!$user_from_cookie) 
            return $this->sendInvalidAttempterResponse();
         
         // send invalid response if otp is not matched
        if($otp_from_client !== $otp_from_cookie ){  
            // if cookie removed or invalidated
            if($otp_from_cookie === '')                  
                return $this->sendInvalidCookieResponse();
            // if otp mismatched
            return $this->sendOtpMismatchResponse();
        }
        
        // find the therapist
        $user = $this->therapistService->findTherapistBySpecificField('email', Crypt::decrypt($user_from_cookie), []);

        // if the user is invalid
        if ($user == null) 
            return $this->sendInvalidUserResponse();    

        // check if the user has already verified account
        if ($user->hasVerifiedEmail()) 
            return $this->sendAlreadyEmailVerifiedResponse();
        
        try{
            // Fire event of registering therapist to sense
            $this->therapistService->updateTherapistDetails($user->id, [
                'email_verified_at' => Carbon::now(),
                'is_active' => true
            ]);
            
            // login user
            $token = $this->attemptUserLogin($user);

            // send verification success response
            return $this->emailVerified($user, $token);

        }catch(Throwable $exception){ 
            // return invalid response
            throw $exception;
            return response()->json([
                'alertType' => 'email-verification-failed',
                'message' => 'Something went wrong! Email verification failed.Please try to re register yourself after some times or contact the support team'
            ], 500);
        }
    
    }

}
