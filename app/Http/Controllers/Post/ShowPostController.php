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
            $post = Post::with('user')->with('images')->with('comments')->with('comments.user')->withCount('reacts')->withCount('comments')->firstOrFail($post_id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }
        $post->user_react_type = optional($post->reacts()->where('user_id', auth()->user()->id)->first())->type;

        return response()->json($post);
    }
}
