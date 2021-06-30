<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Services\Interfaces\ITherapistService;
use App\Traits\Therapist\EmailVerificationTrait;

class ResendOTPNotificationController extends Controller
{
    use EmailVerificationTrait;

    private $therapistService;
    public function __construct(ITherapistService $therapistService)
    {
        $this->therapistService = $therapistService;
        $this->middleware('throttle:5,2');
    }
    
     /**
     *  resend verification link
     */
    public function resend(Request $request)
    {
        // get the attempter cookie
        $attempter = $request->cookie('attempter');

        // return response for invalid attempter
        if(! $attempter)
            return $this->sendInvalidAttempterResponse();

        // Get the user from the database
        $user = $this->therapistService->findTherapistBySpecificField('email', Crypt::decrypt($attempter), []);

        // return response if user not avaliable
        if (!$user) 
            return $this->sendInvalidUserResponse();

        // return response if account is already verified
        if ($user->hasVerifiedEmail()) 
            return $this->sendAlreadyEmailVerifiedResponse();

        // send verification email
        try {
            // generate a 6 digit token
            $token = rand(100000, 999999);    
            
            // delete if there is a previous cookie
            if($request->cookie('OTP_COOKIE')) { cookie()->forget('OTP_COOKIE'); }

            // set the token in a cookie
            $otpCookie = cookie('OTP_COOKIE', Crypt::encrypt($token), 60);  
            
            // send notification with new token
            $user->sendEmailVerificationMail($token);       
            
            // return response for successfull otp resend
            return response()->json([           
                'alertType' => 'otp-resend',
                'message' => 'A new OTP has been sent successfully'
            ], 200)->withCookie($otpCookie);

        } catch (Throwable $e) {
            // return response if the otp send failed
            return response()->json([
                'alertType' => 'internal-server-error',
                'message' => 'Something went wrong ! please try again'
            ], 422);
        }
    }
}
