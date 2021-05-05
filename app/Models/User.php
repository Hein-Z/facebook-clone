<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'profile_image',
        'cover_photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ($password !== null & $password !== "") {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    function friendsOfMine()
    {
        return $this->belongsToMany(User::class, Friend::class, 'user_id', 'friend_id')
            ->wherePivot('accepted_at', '!=', null) // to filter only accepted
            ->withPivot('accepted_at'); // or to fetch accepted value
    }

    function friendOf()
    {
        return $this->belongsToMany(User::class, Friend::class, 'friend_id', 'user_id')
            ->wherePivot('accepted_at', '!=', null)
            ->withPivot('accepted_at');
    }

    public function getFriendsAttribute()
    {
        return $this->friendsOfMine->merge($this->friendOf)->sortByDesc('accepted_at');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reacts()
    {
        return $this->hasMany(React::class);
    }
}
