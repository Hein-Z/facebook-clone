<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Unable to locate the user with the given information.'], 404);
    }
}
