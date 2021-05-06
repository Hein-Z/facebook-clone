<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Traits\SendVerificationCode;
use App\Mail\ResetPassword;
use App\Mail\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use SendVerificationCode;


    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated()
        ));

        $this->sendVerificationCode($user->id, $user->name, $user->email);

        return response()->json([
            'message' => 'User successfully registered, Please check your inbox',
            'user' => $user
        ], 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            if (!$token = auth()->attempt($validator->validated())) {
                return response()->json(['correct_credential' => false, 'email_verified' => false, 'message' => 'Either email or password is wrong.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not create token'
            ], 401);
        }

        if (empty(auth()->user()->email_verified_at)) {
            $this->sendVerificationCode(auth()->user()->id, auth()->user()->name, auth()->user()->email);
            return response()->json(['email_verified' => false, 'correct_credential' => true, 'message' => 'Your have not verified your email.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
