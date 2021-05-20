<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'post_image_path', 'post_image_caption'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getPostImagePathAttribute($value)
    {
        return asset($value);
    }
}
