<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ConfirmSignUp;
use App\Http\Requests\Api\v1\Auth\ResendConfirmationCodeRequest;
use App\Http\Requests\Api\v1\Auth\SignUpRequest;
use App\Interfaces\AuthProviderInterface;
use App\Services\aws\CognitoService;
use Exception;

class AuthController extends Controller
{

    private AuthProviderInterface $authService;

    public function __construct()
    {
        // Here you can init any auth service if you need
        $this->authService = new CognitoService();
    }

    public function signUp(SignUpRequest $request)
    {
        try {
            return $this->responseOk(
                "user created successfully",
                $this->authService->signUp($request->all()),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    public function confirmSignUp(ConfirmSignUp $request)
    {
        try {
            return $this->responseOk(
                "email verified successfully",
                $this->authService->confirmSignUp($request->username, $request->code),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    public function resendConfirmationCode(ResendConfirmationCodeRequest $request)
    {
        try {
            return $this->responseOk(
                "confirmation code sent successfully",
                $this->authService->resendConfirmationCode($request->username),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
