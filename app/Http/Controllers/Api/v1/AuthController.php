<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;
use App\Services\aws\CognitoService;
use Aws\S3\S3Client;
use Exception;

class AuthController extends Controller
{

    public function signUp(RegisterRequest $request)
    {
        try {
            return $this->responseOk(
                "user registered successfully",
                (new CognitoService())->signUp($request->all()),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
