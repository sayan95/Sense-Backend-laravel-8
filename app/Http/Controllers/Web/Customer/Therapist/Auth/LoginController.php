<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Services\Interfaces\ITherapistService;
use App\Http\Resources\Therapist\TherapistResource;

class LoginController extends Controller
{
    private $therapistService;
    public function __construct(ITherapistService $therapistService)
    {
        //$this->middleware("throttle: 15, 3")->only('login');
        $this->therapistService = $therapistService;
    }

    /**
     * validate login credentials
     */
    private function validateLogin(Request $request){
        $this->validator($request->all())->validate();
    }
      
    /**
     *  Login the current user
     */
    public function login(Request $request){
        // validate login credentials
        $this->validateLogin($request); 

        // check if login success or not
        if($this->attemptForLogin($request)){
            return $this->authenticated($request);
        }else{
            return $this->sendFailedLoginResponse($request);
        }
    }
    
    /**
     *  attempt for login
    */
    private function attemptForLogin(Request $request){
        // login credentiald
        $credentials = $request->only("email", "password");

        // check for remember me token
        if($request->has('remember_me') && $request->remember_me === true){
            $token = $this->guard()->setTTL(20160)->attempt($credentials);
        }else{
            $token = $this->guard()->attempt($credentials);
        }

        if(!$token){
            return false;
        }

        // set the user's token
        $this->guard()->setToken($token);
        return true;
    }

    /**
     *  Actions after authentication
     */
    private function authenticated(Request $request){
        // check user has verified email
        $has_verified_email = $this->guard()->user()->is_active;
                
        // if email is not verified
        if(!$has_verified_email){
            // get the auth user
            $therapist = $this->therapistService->findTherapistById($this->guard()->id(), []);
            // generate a 6 digit token
            $token = rand(100000, 999999);  

            // Delete any existing cookie
            if($request->cookie('OTP_COOKIE'))                           
                cookie()->forget('OTP_COOKIE');
            if($request->cookie('attempter'))
                cookie()->forget('attempter');
                
            // set the token in a cookie
            $otpCookie = cookie('OTP_COOKIE', Crypt::encrypt($token), 60);     
            $attemptedUserCookie = cookie('attempter', Crypt::encrypt($therapist->email), 60);
            
            // send the email for otp 
            $therapist->sendEmailVerificationMail($token);                   
            
            // logout user
            $this->guard()->logout();
            $jwtCookie = cookie()->forget('jwt');

            // return response
            return response()->json([
                'alertType' => 'account-not-verified',
                'message' => 'Your account is not verified yet.Enter the OTP sent to tour email to verify your account.',
            ], 422)->withCookie($otpCookie)->withCookie($jwtCookie)->withCookie($attemptedUserCookie);
        }

        // email is verified
        //get the token
        $token = (string)$this->guard()->getToken();

        // set to httponly cookie
        $jwtCookie = cookie('jwt', $token, 60);

        //extract the token's expiary date
        $expiration = $this->guard()->getPayload()->get('exp');
        
        // set logged in time
        $this->therapistService->updateTherapistDetails($this->guard()->id(), [
            'logged_in_at' => Carbon::now()
        ]);

        // success json
        return $this->sendLoginSuccessResponse($token, $expiration, $jwtCookie);
    }

    /**
     *  Send succes login response
     */
    private function sendLoginSuccessResponse($token, $expiration, $jwtCookie){
        // return success message with token
        return response()->json([
            'alertType' => 'login-success',
            'message' => 'You are Logged in successfully',
            'token'=>$token,
            'expires_in' => $expiration,
            'user' => new TherapistResource($this->therapistService->findTherapistById($this->guard()->id(), ['profile']))
        ], 200)->withCookie($jwtCookie);
    }

    /**
     *  Send invalid response
     */
    private function sendFailedLoginResponse(Request $request){
        return response()->json([
            'alertType' => 'credential-error',
            'errors' => [
                'email' => 'Invalid email or password'
            ]
        ], 422);
    }
    
    /**
     *  Request validator
     */
    public function validator(array $data){
        return Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);   
    }

    /**
     *  Default authentication guard
     */
    private function  guard(){
        return auth()->guard('therapist');
    }
}   
