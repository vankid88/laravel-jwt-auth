<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($data, $message) {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message
        ]);
    }

    public function errorResponse($message, $statusCode = 200) {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $statusCode);
    }
}