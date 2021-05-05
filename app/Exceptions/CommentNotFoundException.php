<?php

namespace App\Exceptions;

use Exception;

class CommentNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Unable to locate the comment with the given information.'], 404);
    }
}
