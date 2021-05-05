<?php

namespace App\Http\Controllers\Post;

use App\Exceptions\PostNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function index($post_id)
    {
        try {
            $post_comment = Post::findOrFail($post_id)->comments()->with('user')->latest()->paginate(10);
        }catch (ModelNotFoundException $e){
            throw new PostNotFoundException();
        }

        return response()->json($post_comment);
    }
}
