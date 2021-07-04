<?php

namespace App\Services\Classes;

use App\Repositories\DAL\Criterias\EagerLoad;
use App\Services\Interfaces\ITherapistService;
use App\Repositories\Contracts\TherapistContract;

class TherapistService implements ITherapistService{
    private $therapist;

    public function __construct(TherapistContract $therapist)
    {
        $this->therapist = $therapist;
    }

    // find a therapist by id
    public function findTherapistById($id, array $relations)
    {
        return $this->therapist->withCriterias([new EagerLoad($relations)])->find($id);    
    }

    // find a therapist by specifi field
    public function findTherapistBySpecificField($col, $value, array $relations){
        return $this->therapist->withCriterias([new EagerLoad($relations)])->findWhereFirst($col, $value);
    }

    // add therapist details to database
    public function addTherapist(array $data){
        return $this->therapist->create($data);
    }

    // update therapist data to database
    public function updateTherapistDetails($id, array $data)
    {
        return $this->therapist->update($id, $data);
    }

    // add therapist's profile
    public function addTherpistProfile($email, array $data)
    {
        return $this->therapist->createProfile($email, $data);
    }

    // delete therapist account by id
    public function deleteTherapistAccountById($id){
        $this->therapist->delete($id);
    }

    // delee therapist account by specific field
    public function deleteTherapistAccountByField($col, $value){
        $this->therapist->deleteBySpecificField($col, $value);
    }

    // registers therapist for password reset
    public function registerTherapistForPasswordReset($email, $token){
        $this->therapist->registerForPasswordReset($email, $token);
    }

    // validates password reset request is true or not
    public function validatePasswordResetToken($email, $token){
        return $this->therapist->validatePasswordResetToken($email, $token);
    }

    // dergisters enrty from password reset table
    public function deregisterForPasswordReset($email){
        $this->therapist->deregisterForPasswordReset($email);
    }
}