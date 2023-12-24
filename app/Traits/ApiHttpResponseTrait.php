<?php

namespace App\Traits;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Exception;
use Illuminate\Http\JsonResponse;

trait ApiHttpResponseTrait
{

    /**
     * Global HttpOK response
     *
     * @param string $message
     * @param mixed $payload
     * @param integer $code
     * @return JsonResponse
     */
    public function responseOk(string $message, mixed $payload = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'payload' => $payload,
            'message' => $message,
        ], $code);
    }

    public function responseError(Exception|CognitoIdentityProviderException $exception, array $errors = [])
    {
        $handleErrorObject = [
            'code'    => 400,
            'message' => $exception->getMessage(),
        ];

        if (config('app.env') === 'production') {
            //TODO: Send error to sentry

            $handleErrorObject['message'] = 'have an error';
        }

        if (config('app.env') !== 'production') {
            /**
             * Set message.
             */
            $handleErrorObject = match ((new \ReflectionClass($exception))->getShortName()) {
                'CognitoIdentityProviderException' =>  [
                    'message' => $exception->getAwsErrorMessage(),
                    'code'    => $exception->getStatusCode()
                ],
                default =>  $handleErrorObject,
            };
        }


        return response()->json([
            'status'  => false,
            'message' => $handleErrorObject['message']
        ], $handleErrorObject['code']);
    }
}
