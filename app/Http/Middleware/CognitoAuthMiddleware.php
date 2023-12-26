<?php

namespace App\Http\Middleware;

use App\Models\User;
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
         * Set cognito user to request
         */
        $request->merge(['user' => $result->get('UserAttributes')]);

        /**
         * Set DB user to request
         */
        $request->merge(['user_id' => (User::select('id')->where('cognito_sub', $result->get('UserAttributes')[0]['Value'])->first())?->id]);

        return $next($request);
    }
}
