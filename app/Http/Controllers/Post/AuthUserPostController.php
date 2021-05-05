<?php

namespace App\Http\Controllers\Post;


use App\Exceptions\PostNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthUserPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $posts = $user->posts()->with('images')->latest()->paginate(10);
        $user->posts = $posts;
        return response()->json(['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => [Rule::requiredIf(empty($request->images)), 'string'],
            'images.*' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();


        $post = $user->posts()->create([
            'status' => $request->status
        ]);

        //store each image
        if ($request->hasFile('images')) {
            $images = $request->images;
            foreach ($images as $image) {
                $imagePath = Storage::disk('uploads')->put($user->email . '/post/' . $post->id, $image);
                PostImage::create([
                    'post_image_caption' => $request->status,
                    'post_image_path' => 'upload/' . $imagePath,
                    'post_id' => $post->id
                ]);
            }
        }
        $post->images = $post->images;
        return response()->json(['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'status' => ['string', Rule::requiredIf(empty($request->images))],
            'images.*' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        try {
//            $post = Post::findOrFail($id);
            $user = auth()->user();

            $post = $user->posts()->findOrFail($id);
            if ($post->user_id !== $user->id) {
                return response()->json(['message' => 'you are unauthorized to edit this post'], 401);
            }
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }

        $post->update([
            'status' => $request->status
        ]);

        Storage::disk('uploads')->deleteDirectory($user->email . '/post/' . $post->id);
        PostImage::where('post_id', $post->id)->delete();

//        update image
        if ($request->hasFile('images')) {
            $images = $request->images;
            foreach ($images as $image) {
                $imagePath = Storage::disk('uploads')->put($user->email . '/post/' . $post->id, $image);
                PostImage::create([
                    'post_image_caption' => $request->status,
                    'post_image_path' => 'upload/' . $imagePath,
                    'post_id' => $post->id
                ]);
            }
        }

        $post->images = $post->images;
        return response()->json(['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::with('images')->findOrFail($id);
            if ($post->user_id !== auth()->user()->id) {
                return response()->json(['message' => 'you are unauthorized to delete this post'], 401);
            }
        } catch (ModelNotFoundException $e) {
            throw new PostNotFoundException();
        }


        $post->delete();

        return response()->json(['message' => 'Successfully deleted'], 202);
    }
}
