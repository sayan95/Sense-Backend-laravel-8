<?php

namespace App\Http\Controllers\Web\Customer\Therapist\MyAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Therapist\CreateProfileTrait;
use App\Services\Interfaces\ITherapistService;

class CreateProfileController extends Controller
{   
    use CreateProfileTrait;
    private $therapistService;
    
    public function __construct(ITherapistService $therapistService){
        $this->therapistService = $therapistService;
    }
    
    /**
     *  creates the therapist profile for the first time
     */
    public function createProfile(Request $request, $email){
        // validates incoming request
        $this->validator($request->all())->validate();

        $therapist = $this->therapistService->findTherapistBySpecificField('email', $email, []);

        // creates a profile
        $this->therapistService->updateTherapistDetails($therapist->id, [
            'username' => $request->username,
            'profile_created' => true
        ]);
        $this->therapistService->addTherpistProfile($email, [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
            'pin' => $request->pin,
            'state' => $request->state,
            'country' => $request->country,
            'gender' => $request->gender,
            'language_proficiency' => $request->language_proficiency,
            'education' => $request->education,
            'therapist_profile' => $request->therapist_profile,
            'experties' => $request->experties,
            'spectrum_specialization' => $request->spectrum_specialization,
            'age_group' => $request->age_group
        ]);

        // sends a welcome mail
        $therapist->sendWelcomeMail();
        
        // returrns profile create success response
        return $this->sendValidResponse();
    }
}
