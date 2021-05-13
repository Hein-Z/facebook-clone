<?php

namespace App\Http\Controllers\Comment;

use App\Exceptions\CommentNotFoundException;
use App\Exceptions\PostNotFoundException;
use App\Http\Controllers\Controller;

use App\Models\Comment;
use App\Models\Post;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthUserCommentController extends Controller
{

    public function show(Post $post, $comment_id)
    {
        try {
            $comment = $post->comments()->findOrFail($comment_id);
            $comment->user = $comment->user;
            $comment->post = $comment->post;
        } catch (ModelNotFoundException $e) {
            throw new CommentNotFoundException();
        }
        return response()->json($comment);
    }

    public function store($post, Request $request)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $comment = $post->comments()->create([
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
        ]);

        $comment->user = $comment->user;
        return response()->json($comment);
    }

    public function update($post, Comment $comment, Request $request)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'You are unauthorized to edit this post'], 401);
        }


        try {
            $comment = $post->comments()->findOrFail($comment->id);
            $comment->update([
                'comment' => $request->comment,
                'user_id' => auth()->user()->id,
            ]);
//            $comment->user = $comment->user;
        } catch (ModelNotFoundException $e) {
            throw new CommentNotFoundException();
        }

        return response()->json($comment);
    }

    public function destroy($post, Comment $comment, Request $request)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'You are unauthorized to delete this post'], 401);
        }


        try {
            $comment = $post->comments()->findOrFail($comment->id);
            $comment->delete();
//            $comment->user = $comment->user;
        } catch (ModelNotFoundException $e) {
            throw new CommentNotFoundException();
        }

        return response()->json(['message' => 'successfully deleted your comment']);
    }
}
