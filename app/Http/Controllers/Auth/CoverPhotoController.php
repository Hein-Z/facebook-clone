<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CoverPhotoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover_photo' => 'image|max:2048|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();
        $cover_photo = $request->cover_photo;
        $imagePath = Storage::disk('uploads')->put($user->email . '/cover-photo', $cover_photo);


        return response()->json(['message' => 'successfully added your cover photo image', 'data' => $user->cover_photo]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover_photo' => 'image|max:2048|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();

        Storage::disk('uploads')->delete($user->email . '/cover-photo', $user->cover_photo);

        $cover_photo = $request->cover_photo;
        $imagePath = Storage::disk('uploads')->put($user->email . '/cover-photo', $cover_photo);


        $user->update([
            'cover_photo' => $imagePath
        ]);

        return response()->json(['message' => 'successfully updated your cover photo', 'data' => $user->cover_photo]);
    }
}
