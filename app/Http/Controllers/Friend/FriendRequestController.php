<?php

namespace App\Http\Controllers\Friend;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class FriendRequestController extends Controller
{
    public function store($friend_id)
    {
        try {
            User::findOrFail($friend_id)
                ->friendRequests()->syncWithoutDetaching(auth()->user()->id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        } catch (QueryException $e) {
        }

        return response()->json(['message' => 'successfully added friend'], 202);

    }
    public function destroy($friend_id)
    {
        try {
            User::findOrFail($friend_id)
                ->friendRequests()->detach(auth()->user()->id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        } catch (QueryException $e) {
        }

        return response()->json(['message' => 'finish canceling friend request'], 202);
    }
}
