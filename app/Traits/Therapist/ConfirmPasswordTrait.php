<?php

namespace App\Traits\Therapist;

use Illuminate\Support\Facades\Validator;

trait ConfirmPasswordTrait{

    // user request validation
    protected function validator(array $data){
        return Validator::make($data, [
            'password' => ['required', 'min:6', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/', 'confirmed'],
            'password_confirmation' => ['required']
        ],[
            'password.required' => 'Please enter a new password',
            'password.min' => 'This password did not match our password policy',
            'password.regex' => 'This password did not match our password policy',
            'password.confirmed' => 'Password did not matched',
            'password_confirmation' => 'Please confirm your new password'
        ]);
    }

    // returns invalid route signature response
    protected function sendInvalidRouteSignatureResponse(){
        return response()->json([
            'alertType' => 'invalid-reset-url',
            'message' => 'This password reset link is invalid. Please request for a new reset link'
        ], 403);
    }
    
    // returns password change aproval rsponse
    protected function approvePasswordChangeResponse(){
        return response()->json([
            'alertType' => 'password-rest-approved',
            'message' => 'Password reset is approved.'
        ], 200);
    }

    // return deny for password change response
    protected function denyPasswordChangeResponse(){
        return response()->json([
            'alertType' => 'password-reset-denied',
            'message' => 'This is an invalid password change request.'
        ], 403);
    }

    // return password change success response
    protected function sendPassswordChangeSuccessResponse(){
        return response()->json([
            'alertType' => 'password-reset-success',
            'message' => 'Your password has been changed successfully. Try to sign in with new password'
        ], 200);
    }
}