<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\SendVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserVerificationController extends Controller
{
    use SendVerificationCode;

    public function verifyUser(Request $request)
    {
        $checks = DB::table('user_verifications')->where('verification_code', $request->verification_code)->get();

        if (!$checks->isEmpty()) {
            foreach ($checks as $check) {
                $check_user = User::find($check->user_id);
                if ($check_user->email === $request->email) {
                    $user = $check_user;
                }
            }

            if (!isset($user)) {
                return response()->json(['message' => "Verification code or email is invalid."], 404);
            }

            if ($user->email_verified_at) {
                return response()->json([
                    'message' => 'Account already verified..',
                ]);
            }

            $user->update(['email_verified_at' => now()]);
            DB::table('user_verifications')->where('verification_code', $request->verification_code)->where('user_id', $user->id)->delete();

            return response()->json([
                'message' => 'You have successfully verified your email address.',
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
