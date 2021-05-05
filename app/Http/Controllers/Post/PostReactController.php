<?php

namespace App\Http\Controllers\Post;

use App\Exceptions\PostNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostReactController extends Controller
{
    public function index($post_id)
    {
        try {
            $post = Post::with('reacts.user')->findorFail($post_id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        return response()->json($post);
    }

}
