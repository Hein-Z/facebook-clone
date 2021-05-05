<?php

namespace App\Http\Traits;

use App\Mail\UserVerification;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait SendVerificationCode
{
    public function sendVerificationCode($user_id, $user_name, $user_email)
    {
        $verification_code = substr(str_shuffle("0123456789"), 0, 8);

        DB::table('user_verifications')->upsert([
            ['user_id' => $user_id, 'verification_code' => $verification_code]
        ], ['user_id'], ['verification_code']);


        $subject = "Please verify your email address.";

        Mail::to($user_email)->send(new UserVerification($subject, $verification_code, $user_name));
    }
}
