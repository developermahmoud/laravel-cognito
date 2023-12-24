<?php

namespace App\Services\aws;

use App\Interfaces\AuthProviderInterface;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Credentials\CredentialProvider;

class CognitoService implements AuthProviderInterface
{
    private CognitoIdentityProviderClient $client;
    private string $clientID;

    public function __construct()
    {
        $this->client = new CognitoIdentityProviderClient([
            'credentials' => CredentialProvider::env(),
            'region'      => 'eu-central-1',
        ]);

        $this->clientID = config('services.cognito.client_id');
    }

    public function signUp(array $data): string
    {
        if ($result = $this->client->signUp([
            'ClientId' => $this->clientID,
            'Username' => $data['email'],
            'Password' => $data['password'],
            'UserAttributes' => [
                [
                    'Name' => 'custom:first_name',
                    'Value' => $data['first_name'],
                ],
                [
                    'Name' => 'custom:last_name',
                    'Value' => $data['last_name'],
                ],
            ],
        ])) {
            return $result->get('UserSub');
        }
    }

    public function confirmSignUp(string $username, string $code)
    {
        $this->client->confirmSignUp([
            'ClientId'         => $this->clientID,
            'ConfirmationCode' => $code,
            'Username'         => $username
        ]);
    }

    public function resendConfirmationCode(string $username)
    {
        $this->client->resendConfirmationCode([
            'ClientId'         => $this->clientID,
            'Username'         => $username
        ]);
    }
}
