<?php

namespace App\Traits\Therapist;

use Illuminate\Support\Facades\Validator;

trait ForgetPasswordTrait{

    // validate user request
    protected function valiadator(array $data){
        return Validator::make($data, [
            'email' => ['required', 'email']
        ]);
    }
    // email not found response
    protected function sendEmailInavlidResponse(){
        return response()->json([
            'alertType' => 'email-not-found',
            'message' => 'Sorry, email was not found'
        ], 404);
    }

    // send reset link send reposne
    protected function sendResetLinkSentResponse(){
        return response()->json([
            'alertType'=> 'reset-link-sent',
            'message' => 'A password reset link has been sent to your email'
        ], 200);
    }
}