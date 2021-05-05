<?php

namespace App\Http\Controllers\Friend;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use Illuminate\Http\Request;

class AuthUserFriendController extends Controller
{
    public function index()
    {
        auth()->user()->friends = auth()->user()->friends;
        return response()->json(['user' => auth()->user()]);
    }
}
