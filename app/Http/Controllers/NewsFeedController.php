<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;

class NewsFeedController extends Controller
{
    public function index()
    {
        $friends = Friend::friendships();

        if ($friends->isEmpty()) {
            return response()->json(auth()->user()->posts);
        }
//
        $posts = Post::with('user')->with('images')->whereIn('user_id', $friends->pluck('user_id')->merge($friends->pluck('friend_id'))->unique())
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
            ->latest()->paginate(5);

        foreach ($posts as $post) {
            $post->user_react_type = optional($post->reacts()->where('user_id', auth()->user()->id)->first())->type;
        }

        return response()->json($posts);

    }
}
