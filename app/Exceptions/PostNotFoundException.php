<?php

namespace App\Exceptions;

use Exception;

class PostNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Unable to locate the post with the given information.'], 404);
    }
}
