<?php

namespace App\Http\Resources\Therapist;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TherapistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username' => $this->username ? $this->username : 'not set yet',
            'email' => $this->email, 
            'user_type' => $this->user_type,
            'profile' => new TherapistProfileResource($this->whenLoaded('profile')),
            'dates' => [
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at->format('jS F Y, g:i a'),
                'updated_at' => $this->updated_at->format('jS F Y, g:i a'),
                'logged_in_at' => $this->logged_in_at ? Carbon::parse($this->logged_in_at)->timezone('Asia/kolkata')->format('jS F Y, g:i a') : null 
            ], 
            'account_status' => [
                'is_published' =>  $this->is_active ? 'published' : 'waiting', 
                'is_active'=> $this->is_active ? 'verified' : 'pending', 
                'profile_created' => $this->profile_created
            ]
        ];
    }
}
