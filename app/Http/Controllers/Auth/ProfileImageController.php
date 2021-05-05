<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();
        $profile_image = $request->profile_image;
        $imagePath = Storage::disk('uploads')->put($user->email . '/profile-image', $profile_image);


        $user->update([
            'profile_image' => $imagePath
        ]);

        return response()->json($user->profile_image);
    }
}
