<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function resetPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }

    private function updatePasswordRow($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    private function tokenNotFoundError()
    {
        return response()->json([
            'message' => 'either your email or token is wrong'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function resetPassword($request)
    {
        $user_data = User::where('email', $request->email)->first();
        $user_data->update([
            'password' => $request->password
        ]);

        $this->updatePasswordRow($request)->delete();

        return \response()->json([
            'message' => 'Password is changed successfully'
        ]);
    }


}
