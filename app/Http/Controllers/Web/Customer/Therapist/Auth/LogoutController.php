<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    // logout therapist
    public function logout(Request $request){
        $this->guard()->logout(true);
        $jwtCookie = cookie()->forget('jwt');
        return $this->sendLogoutResponse($jwtCookie);
    }

    /**
     *  therapist success logout response
     */
    private function sendLogoutResponse($jwtCookie){
        return response()->json([
            'alertType' => 'user-logout',
            'message'=> 'You are logged out successfully'
        ], 200)->withCookie($jwtCookie);
    }

    /**
     *  default therapist auth guard
     */
    private function guard(){
        return auth()->guard('therapist');
    }
}
