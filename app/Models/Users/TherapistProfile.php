<?php

namespace App\Models\Users;

use App\Models\BaseModel as Eloquent;

class TherapistProfile extends Eloquent
{
    // fillable properties
    protected $fillable = [
        'therapist_id', 'firstname', 'lastname', 'phone', 'gender',
        'address', 'pin', 'state', 'country',
        'language_proficiency', 'education', 'therapist_profile', 
        'experties', 'spectrum_specialization', 'age_group'
    ];

    // casts to array
    protected $casts = [
        'experties' => 'array',
        'education' => 'array',
        'age_group' => 'array',
        'therapist_profile' => 'array', 
        'language_proficiency' => 'array',
        'spectrum_specialization' => 'array',
    ];
    
    // relationships
    public function therapist(){
        return $this->belongsTo(Therapist::class);
    }
}
