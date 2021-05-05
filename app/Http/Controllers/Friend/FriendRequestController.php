<?php

namespace App\Http\Controllers\Friend;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Friend as FriendResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendRequestController extends Controller
{
    public function store($friend_id)
    {
        try {
            User::findOrFail($friend_id)
                ->friendOf()->syncWithoutDetaching(auth()->user()->id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        } catch (QueryException $e) {
        }

        return new FriendResource(
            Friend::where('user_id', auth()->user()->id)
                ->where('friend_id', $friend_id)
                ->first()
        );

    }
}
