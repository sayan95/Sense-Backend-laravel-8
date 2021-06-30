<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    
    public function __construct()
    {
        $this->middleware("throttle: 15, 3")->only('login');
    }

    /**
     * validate login credentials
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:50',
            'password' => 'required|string',
        ]);
    }

    
    /**
     *  Login the current user
     */
    public function login(Request $request){
        $this->validateLogin($request);
        
    }
    
    /**
     *  attempt for login
    */
    

    /**
     *  Send succes login response
     */

    /**
     *  Send invalid response
     */

    /**
     *  Default authentication guard
     */
    private function  guard(){
        return auth()->guard('therapist');
    }
}   
