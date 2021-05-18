<?php

namespace App\Http\Controllers\Friend;

use App\Http\Controllers\Controller;
use App\Models\Friend;

class AuthUserFriendController extends Controller
{
    public function index()
    {
        auth()->user()->friends = auth()->user()->friends;
        return response()->json(['user' => auth()->user()]);
    }

    public function unfriend($user_id)
    {

        $friend = Friend::isFriend($user_id);

        if ($friend) {
            $friend->delete();
            return response()->json(['message' => 'successfully ignore friend request'], 202);
        }

        return response()->json([
            'message' => 'Unable to locate the user with the given information.'], 404);


    }
}
