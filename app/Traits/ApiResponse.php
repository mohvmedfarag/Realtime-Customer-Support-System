<?php

namespace App\Traits;

Trait ApiResponse
{
    public function apiError($message, $code = 400)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $code);
    }
}
