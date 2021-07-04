<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\BaseContract;

interface TherapistContract extends BaseContract{
    public function createProfile($email, array $data);
    public function registerForPasswordReset($email, $token);
    public function validatePasswordResetToken($email, $token);
    public function deregisterForPasswordReset($email);
}