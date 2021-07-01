<?php

namespace App\Http\Controllers\Web\Customer\Therapist\MyAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Interfaces\ITherapistService;
use App\Http\Resources\Therapist\TherapistResource;
use Illuminate\Support\Facades\Redis;

class MeController extends Controller
{
    private $therapistService;

    // attaching middlewares to the controller
    public function __construct(ITherapistService $therapistService)
    {
        $this->therapistService = $therapistService;
    }

    // return the authenticated therapist profile
    public function me()
    {
        $therapist = Cache::remember('therapist'.$this->guard()->id(), 60, function () {
            return new TherapistResource(
                $this->therapistService->findTherapistById($this->guard()->id(), ['profile'])
            );
        });
        //Cache::store('redis')->put('therapist'.auth()->guard('therapist')->id(), json_encode($therapist));
        return response()->json([
            'alertType' => 'user-authenticated',
            'user' => $therapist
        ], 200);   
    }

    // returns the authenticated therapist guard
    private function guard()
    {
        return auth()->guard('therapist');
    }
}
