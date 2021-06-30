<?php

namespace App\Traits\Therapist;

use Carbon\Carbon;
use App\Http\Resources\Therapist\TherapistResource;

trait EmailVerificationTrait
{
    /**
     *  attempt user login
     */
    protected function attemptUserLogin($user)
    {
        $token = $this->guard()->login($user);
        $this->guard()->setToken($token);
        $this->therapistService->updateTherapistDetails($this->guard()->id(), [
            'logged_in_at' => Carbon::now()
        ]);
        return $token;
    }

    /**
     *  Send verfication success response
     */
    protected function emailVerified($user, $token)
    {
        // set to httponly cookie
        $jwtCookie = cookie('jwt', $token, 60);
        // remove otp cookie
        $otpCookie = cookie()->forget('OTP_COOKIE');
        // remove attempter cookie
        $attempterCookie = cookie()->forget('attempter');

        //extract the token's expiary date
        $expiration = $this->guard()->getPayload()->get('exp');

        // send the verification success message 
        return $this->sendVerificationSuccessResponse($user, $expiration, $jwtCookie, $otpCookie, $attempterCookie);
    }
    /**
     *  returns verification success response
     */
    protected function sendVerificationSuccessResponse($user, $expiration, $jwtCookie, $otpCookie, $attempterCookie)
    {
        // send the verification success message 
        return response()->json([
            'alertType' => 'verification-success',
            'message' => 'Your account has been verified successfully.',
            'user' => new TherapistResource($user),
            'sessionTimeOut' => $expiration
        ], 200)->withCookie($jwtCookie)
            ->withCookie($otpCookie)
            ->withCookie($attempterCookie);
    }

    /**
     *  returns response for invalid attempter
     */
    protected function sendInvalidAttempterResponse()
    {
        return response()->json([
            'alertType' => 'invalid-attempter',
            'message' => 'current session is over. Try to login again'
        ], 403);
    }

    /**
     *  returns invalid cookie response
     */
    protected function sendInvalidCookieResponse()
    {
        return response()->json([
            'alertType' => 'otp-timeout',
            'message' => 'Your current session is over. Click the resend link for a new OTP'
        ], 422);
    }

    /**
     *  returns invalid otp mismatch response
     */
    protected function sendOtpMismatchResponse()
    {
        return response()->json([
            'alertType' => 'otp-mismatch',
            'message' => 'Invalid OTP entered'
        ], 422);
    }

    /**
     *  returns invalid user response
     */
    protected function sendInvalidUserResponse()
    {
        return response()->json([
            'alertType' => 'therapist-not-found-error',
                'message' => "We can't find a user with that email address."
        ], 422);
    }

    /**
     *  returns already verified user response
     */
    protected function sendAlreadyEmailVerifiedResponse()
    {
        return response()->json([
            'alertType' => 'already-verified-account',
            'message' => 'Your account is already verified'
        ], 422);
    }

    /**
     *  default auth guard for therapist
     */
    protected function guard()
    {
        return auth()->guard('therapist');
    }
}
