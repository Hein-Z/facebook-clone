<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['accepted_at'];

    public static function isFriend($userId)
    {
        return (new static())
            ->where(function ($query) use ($userId) {
                return $query->where('user_id', auth()->user()->id)
                    ->where('friend_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                return $query->where('friend_id', auth()->user()->id)
                    ->where('user_id', $userId);
            })->whereNotNull('accepted_at')
            ->first();
    }

    public static function friendshipStatus($user_id)
    {
        $auth_id = auth()->user()->id;


        if ($auth_id == $user_id) {
            return 'auth user';
        }

        $friendship = (new static())
            ->where(function ($query) use ($user_id, $auth_id) {
                return $query->where('user_id', $auth_id)
                    ->where('friend_id', $user_id);
            })
            ->orWhere(function ($query) use ($user_id, $auth_id) {
                return $query->where('friend_id', $auth_id)
                    ->where('user_id', $user_id);
            })->first();

        if ($friendship) {
            if ($friendship->accepted_at != null) {
                return 'friend';
            } else if ($friendship->user_id == $user_id) {
                return 'get request';
            } else if ($friendship->friend_id == $user_id) {
                return 'pending';
            }
        }

        return 'not friend';
    }

    public static function friendships()
    {
        return (new static())
            ->whereNotNull('accepted_at')
            ->where(function ($query) {
                return $query->where('user_id', auth()->user()->id)
                    ->orWhere('friend_id', auth()->user()->id);
            })
            ->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
