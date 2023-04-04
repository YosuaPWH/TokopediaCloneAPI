<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Response extends Controller
{
    static function error($message = "fails")
    {
        return response()->json([
            'status' => 400,
            'message' => $message
        ], 400);
    }

    static function success($data, $message = "success")
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }
}
