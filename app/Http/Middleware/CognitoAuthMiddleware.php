<?php

namespace App\Http\Middleware;

use App\Services\aws\CognitoService;
use App\Traits\ApiHttpResponseTrait;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CognitoAuthMiddleware
{
    use ApiHttpResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!request()->header("authorization")) {
            abort(401);
        }

        /**
         * Get Access Token
         */
        $accessToken = explode(" ", request()->header("authorization"))[1];

        /**
         * Init cognito service
         */
        $cognitoService =  new CognitoService();

        /**
         * Get user by access token
         */
        try {
            $result = $cognitoService->getUser($accessToken);
        } catch (Exception $e) {
            return $this->responseError($e);
        }


        /**
         * Set user to request
         */
        $request->merge(['user' => $result->get('UserAttributes')]);

        return $next($request);
    }
}
