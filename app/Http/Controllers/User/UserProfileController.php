<?php

namespace App\Http\Controllers\User;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserProfileController extends Controller
{
    public function index($user_id)
    {

        try {
            $user = User::findOrFail($user_id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        $user->friendshipStatus = Friend::friendshipStatus($user_id);

        $posts = $user->posts()->with('images')
            ->withCount([
                'reacts as like_count' => function ($query) {
                    $query->where('type', 'like');
                },
                'reacts as love_count' => function ($query) {
                    $query->where('type', 'love');
                },
                'reacts as sad_count' => function ($query) {
                    $query->where('type', 'sad');
                },
                'reacts as haha_count' => function ($query) {
                    $query->where('type', 'haha');
                },
                'reacts as wow_count' => function ($query) {
                    $query->where('type', 'wow');
                },
                'reacts as angry_count' => function ($query) {
                    $query->where('type', 'angry');
                },
            ])->withCount('comments')
            ->latest()
            ->paginate(5);

        foreach ($posts as $post) {
            $post->user_react_type = optional($post->reacts()->where('user_id', auth()->user()->id)->first())->type;
        }
        $user->posts = $posts;
        return response()->json(['user' => $user]);
    }
}
