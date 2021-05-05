<?php

namespace App\Http\Controllers\Friend;


use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserFriendsController extends Controller
{
    public function index($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }
        $user->friends = $user->friends;
        return response()->json(['user' => $user]);

    }
}
