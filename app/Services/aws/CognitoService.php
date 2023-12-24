<?php

namespace App\Services\aws;

use App\Interfaces\AuthProviderInterface;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Credentials\CredentialProvider;

class CognitoService implements AuthProviderInterface
{
    private CognitoIdentityProviderClient $client;
    private string $clientID = "153bmskr4p511qg2q5ma3apeu7";

    public function __construct()
    {
        $this->client = new CognitoIdentityProviderClient([
            'credentials' => CredentialProvider::env(),
            'region'      => 'eu-central-1',
        ]);
    }

    public function signUp(array $data)
    {
        if ($result = $this->client->signUp([
            'ClientId' => $this->clientID,
            'Username' => $data['email'],
            'Password' => $data['password'],
        ])) {
            return $result->get('UserSub');
        }
    }
}
