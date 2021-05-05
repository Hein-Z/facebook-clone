<?php

namespace App\Http\Controllers\Friend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Friend as FriendResource;
use App\Exceptions\UserNotFoundException;
use App\Models\Friend;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class FriendRequestResponseController extends Controller
{
    public function accept($user_id)
    {
        try {
            $friend = Friend::where('friend_id', auth()->user()->id)->where('user_id', $user_id)->firstOrFail();
            $friend->update([
                'accepted_at' => now()
            ]);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        return response()->json(new FriendResource($friend));


    }

    public function ignore($user_id)
    {
        try {
            $friend = Friend::where('friend_id', auth()->user()->id)->where('user_id', $user_id)->firstOrFail();
            if ($friend->accepted_at) {
                return response()->json(['errors' => [
                    'code' => 404,
                    'title' => 'already friend',
                    'detail' => 'you are already friend with this user']], 404);
            }

            $friend->delete();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        return response()->json(['message' => 'successfully ignore friend request',], 202);

    }
}
