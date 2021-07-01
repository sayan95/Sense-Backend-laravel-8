<?php

namespace App\Http\Controllers\Web\Customer\Therapist\Auth;

use App\Http\Controllers\Controller;
use App\Rules\Therapist\Auth\IsCorrectPassword;
use App\Rules\Therapist\Auth\PreventSamePasswords;
use App\Services\Interfaces\ITherapistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    private ITherapistService $therapistService;
    public function __construct(ITherapistService $therapistService){
        $this->therapistService = $therapistService;
    }

    /**
     *  resets therapist account password
     */
    public function resetPassword(Request $request){
        $this->validator($request->all())->validate();
        $currentUserId = auth()->guard('therapist')->id();
        $this->therapistService->updateTherapistDetails($currentUserId, [
            'password' => bcrypt($request->password)
        ]);
        return $this->sendResetSuccessResponse();
    }

    /**
     *  returns password reset successful response
     */
    private function sendResetSuccessResponse(){
        return response()->json([
            'alertType' => 'password-reset-successful',
            'message' => 'Your password has been updated successfully'
        ], 204);
    }

    /**
     *  validates incoming request
     */
    private function validator(array $data){
        $IsCorrectPassword = new IsCorrectPassword(auth()->guard('therapist')->user());
        $PreventSamePasswords = new PreventSamePasswords($data['current_password']);
        return Validator::make($data, [
            'current_password' => ['required', $IsCorrectPassword],
            'password' => ['required', 'min:6', $PreventSamePasswords, 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/', 'confirmed', ],
            'password_confirmation' => ['required']
        ]);
    }

}
