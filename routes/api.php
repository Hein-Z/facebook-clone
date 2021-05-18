<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group([
    'prefix' => 'auth',
], function ($router) {

    Route::post('register', 'Auth\AuthController@register');
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('sendPasswordResetLink', 'Auth\PasswordResetRequestController@sendEmail');
    Route::patch('resetPassword', 'Auth\ResetPasswordController@resetPasswordProcess');
    Route::post('verifyUser', 'Auth\UserVerificationController@verifyUser');
    Route::post('sendVerificationCode', 'Auth\UserVerificationController@sendVerificationCodeProcess');
    Route::post('logout', 'Auth\AuthController@logout');

    Route::post('upload-profile-image', 'Auth\ProfileImageController@store');
    Route::patch('update-profile-image', 'Auth\ProfileImageController@update');

    Route::post('upload-cover-photo', 'Auth\CoverPhotoController@store')->middleware('auth.jwt');
    Route::patch('update-cover-photo', 'Auth\CoverPhotoController@update')->middleware('auth.jwt');

    Route::post('me', 'Auth\AuthController@me')->middleware('auth.jwt');
});

Route::get('friends', 'Friend\AuthUserFriendController@index')->middleware('auth.jwt');

Route::get('users/{user_id}/profile', 'User\UserProfileController@index')->middleware('auth.jwt');
Route::post('users/{friend_id}/add-friend', 'Friend\FriendRequestController@store')->middleware('auth.jwt');
Route::post('users/{friend_id}/cancel-friend-request', 'Friend\FriendRequestController@destroy')->middleware('auth.jwt');
Route::post('users/{user_id}/accept-friend-request', 'Friend\FriendRequestResponseController@accept')->middleware('auth.jwt');
Route::post('users/{user_id}/ignore-friend-request', 'Friend\FriendRequestResponseController@ignore')->middleware('auth.jwt');
Route::post('users/{user_id}/unfriend', 'Friend\AuthUserFriendController@unfriend')->middleware('auth.jwt');

Route::get('users/{user_id}/friends', 'Friend\UserFriendsController@index')->middleware('auth.jwt');
Route::get('users/{user_id}/posts', 'Post\UserPostController@index')->middleware('auth.jwt');

Route::get('posts/{post_id}', 'Post\ShowPostController@index')->middleware('auth.jwt');
Route::resource('posts', 'Post\AuthUserPostController')->except(['create', 'edit'])->middleware('auth.jwt');
Route::resource('posts.comments', 'Comment\AuthUserCommentController')->except(['index', 'create', 'edit'])->middleware('auth.jwt');
Route::get('posts/{post_id}/comments', 'Post\PostCommentController@index')->middleware('auth.jwt');
Route::get('posts/{post_id}/reacts', 'Post\PostReactController@index')->middleware('auth.jwt');
Route::post('posts/{post_id}/add-react', 'React\AuthUserReactController@store')->middleware('auth.jwt');
Route::post('posts/{post_id}/remove-react', 'React\AuthUserReactController@destroy')->middleware('auth.jwt');

Route::get('news-feed', 'NewsFeedController@index')->middleware('auth.jwt');

Route::get('/{any}', function () {
    return response()->json(['message' => 'your request url not found'], 404);
})->where('any', '.*');
