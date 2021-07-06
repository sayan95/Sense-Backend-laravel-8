<?php

namespace App\Http\Controllers\Web\Customer\Therapist\MyAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Interfaces\ITherapistService;
use App\Http\Resources\Therapist\TherapistResource;

class MeController extends Controller
{
    private $therapistService;

    public function __construct(ITherapistService $therapistService)
    {
        $this->therapistService = $therapistService;
    }

    /**
     *  return the authenticated therapist profile
     */
    public function me()
    {
        $therapist = Cache::remember('therapist'.$this->guard()->id(), 60, function () {
            return new TherapistResource(
                $this->therapistService->findTherapistById($this->guard()->id(), ['profile'])
            );
        });
        
        return response()->json([
            'alertType' => 'user-authenticated',
            'user' => $therapist
        ], 200);   
    }

    /**
     *  returns the authenticated therapist guard
     */
    private function guard()
    {
        return auth()->guard('therapist');
    }
}
