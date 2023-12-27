<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ConfirmSignUp;
use App\Http\Requests\Api\v1\Auth\ResendConfirmationCodeRequest;
use App\Http\Requests\Api\v1\Auth\SignInRequest;
use App\Http\Requests\Api\v1\Auth\SignUpRequest;
use App\Http\Resources\Api\v1\Auth\UserResource;
use App\Interfaces\AuthProviderInterface;
use App\Services\aws\CognitoService;
use App\Services\UserService;
use Exception;

class AuthController extends Controller
{

    private AuthProviderInterface $authService;

    public function __construct(private readonly UserService $userService)
    {
        // Here you can init any auth service as you need
        $this->authService = new CognitoService();
    }

    public function signUp(SignUpRequest $request)
    {
        try {
            /**
             * Add user to cognito
             */
            $cognito_sub = $this->authService->signUp($request->all());

            /**
             * add user to our DB
             */
            $this->userService->store($request->email, $cognito_sub);

            return $this->responseOk(
                __("user created successfully"),
                $cognito_sub,
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    public function confirmSignUp(ConfirmSignUp $request)
    {
        try {
            return $this->responseOk(
                __("email verified successfully"),
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
                __("confirmation code sent successfully"),
                $this->authService->resendConfirmationCode($request->username),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    public function signIn(SignInRequest $request)
    {
        try {
            $result = $this->authService->initiateAuth($request->username, $request->password);

            return $this->responseOk(
                __("user logged in successfully"),
                $result->get('AuthenticationResult'),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }

    public function user()
    {
        try {
            return $this->responseOk(
                __("user"),
                new UserResource(request()->user),
            );
        } catch (Exception $e) {
            return $this->responseError($e);
        }
    }
}
