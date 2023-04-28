<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function sendSuccessResponse($message='Completed Successfully', $data=null) {
        return response(array('success' => true, 'message' => $message, 'data' => $data), 200);
    }

    protected function sendErrorResponse($message='Something Went Wrong', $data=null, $code=403) {
        return response(array('success' => false, 'message' => $message, 'data' => $data), $code);
    }
}
