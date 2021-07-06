<?php

namespace App\Traits\Therapist;

use Illuminate\Support\Facades\Validator;

trait CreateProfileTrait{

    // validate requests
    protected function validator($data){
        return Validator::make($data, [
            'username' => ['required', 'max:50', 'unique:therapists'],
            'firstname' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'max:10', 'min:10', 'unique:therapist_profiles'],
            'address' => '',
            'pin' => '',
            'state' => '',
            'country' => '',
            'gender' => ['required'],
            'language_proficiency' => ['required'],
            'education' => ['required'],
            'therapist_profile' => ['required'],
            'experties' => ['required'],
            'spectrum_specialization' => ['required'],
            'age_group' => ['required']
        ]);
    } 

    // returns profile create success response
    protected function sendValidResponse(){
        return response()->json([
            'alertType' => 'profile-create-success',
            'message' => 'Your profile has been created successfully! Thanks for joining us. We will catch you soon.'
        ], 201);
    }
}