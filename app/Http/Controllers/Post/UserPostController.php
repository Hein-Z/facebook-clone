<?php

namespace App\Http\Controllers\Post;


use App\Exceptions\PostNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        try {
            $user = User::with('images')->findOrFail($user_id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        $posts = $user->posts()->with('images')->paginate(10);
        $user->posts = $posts;
        return response()->json(['user' => $user]);
    }

}
