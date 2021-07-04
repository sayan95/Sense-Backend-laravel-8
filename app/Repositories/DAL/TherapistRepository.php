<?php

namespace App\Repositories\DAL;

use Carbon\Carbon;
use App\Models\Users\Therapist;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\TherapistContract;

class TherapistRepository extends BaseRepository implements TherapistContract{

    public function model(){
        return Therapist::class;
    }

    // creates therapist profile
    public function createProfile($email, array $data){
        $therapist = $this->findWhereFirst('email', $email);
        return $therapist->profile()->create($data);
    }

    // adds therapist entry to reset password
    public function registerForPasswordReset($email, $token){
        $table = 'password_resets';
        $record = DB::table($table)->where('email', $email)->first();

        if($record){
            DB::table($table)->where('email', $email)->update([
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }else{
            DB::table($table)->insert([
                'email' => $email,
                'user_type' => 'therapist',
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }
    }

    // validates if password reset request is valid or not
    // returns bool
    public function validatePasswordResetToken($email, $token){
        $table = 'password_resets';

        $ifTokenExists = DB::table($table)->where('email', $email)->first();

        if($ifTokenExists && $token === $ifTokenExists->token)
            return true;

        return false;
    }
    
    // deregisters a entry from password reset table
    public function deregisterForPasswordReset($email){
        $table = 'password_resets';
        DB::table($table)->where('email', $email)->delete();
    }
}