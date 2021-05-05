<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Traits\SendVerificationCode;
use App\Mail\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserVerificationController extends Controller
{
    use SendVerificationCode;

    public function verifyUser(Request $request)
    {
        $check = DB::table('user_verifications')->where('verification_code', $request->verification_code)->first();

        if (!is_null($check)) {
            $user = User::find($check->user_id);

            if ($user->email !== $request->email) {
                return response()->json(['message' => "Verification code or email is invalid."], 404);
            }

            if ($user->email_verified_at) {
                return response()->json([
                    'message' => 'Account already verified..'
                ]);
            }

            $user->update(['email_verified_at' => now()]);
            DB::table('user_verifications')->where('verification_code', $request->verification_code)->delete();

            return response()->json([
                'message' => 'You have successfully verified your email address.'
            ]);
        }

        return response()->json(['message' => "Verification code is invalid."], 404);

    }

    public function sendVerificationCodeProcess(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $this->sendVerificationCode($user->id, $user->name, $request->email);

        return response()->json([
            'message' => 'verification code sent']);
    }
}

