<?php

namespace App\Http\Controllers\Post;

use App\Exceptions\PostNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowPostController extends Controller
{
    public function index($post_id)
    {
        try {
            $post = Post::with('user')->with('images')->withCount([
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
            ])->withCount('comments')->findOrFail($post_id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }
        $post->comments = $post->comments()->with('user')->latest()->paginate(5);
        $post->user_react_type = optional($post->reacts()->where('user_id', auth()->user()->id)->first())->type;

        return response()->json($post);
    }
}
