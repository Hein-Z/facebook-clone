<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function sendEmail(Request $request)
    {
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }

        return $this->send($request->email);
    }


    public function send($email)
    {
        $token = $this->createToken($email);

        $sendMail = Mail::to($email)->send(new ResetPassword($token, $email));

        if (empty($sendMail)) {
            return $this->successResponse();
        } else {
            return $this->emailNotSentResponse();
        }
    }

    public function createToken($email)
    {
        $oldToken = DB::table('password_resets')->where('email', $email)->first('token');

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(40);
        $this->saveToken($token, $email);

        return $token;
    }

    public function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function successResponse()
    {
        return response()->json(['message' => 'password reset form link is sent successfully, please check your inbox'], Response::HTTP_ACCEPTED);
    }

    public function failedResponse()
    {
        return response()->json(['message' => 'email does not found'], Response::HTTP_NOT_FOUND);
    }

    public function emailNotSentResponse()
    {
        return response()->json(['message' => 'something wrong, email cannot be sent'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


}
