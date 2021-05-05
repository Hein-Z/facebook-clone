<?php

namespace App\Http\Controllers\React;

use App\Exceptions\PostNotFoundException;
use App\Http\Controllers\Controller;

use App\Models\Post;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AuthUserReactController extends Controller
{
    public function store($post_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['like', 'love', 'haha', 'sad', 'wow', 'angry'])]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),404);
        }

        try {
            $post = Post::findOrFail($post_id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        $react = $post->reacts()->updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['type' => $request->type]);

        return response()->json($react);
    }

    public function destroy($post_id)
    {

        try {
            $post = Post::findOrFail($post_id);
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        $post->reacts()->where('user_id', auth()->user()->id)->delete();

        return response()->json(['message' => 'your react successfully removed']);
    }
}
