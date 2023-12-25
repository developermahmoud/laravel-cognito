<?php

namespace App\Interfaces;

interface AuthProviderInterface
{
    public function signUp(array $data): string;
    public function confirmSignUp(string $username, string $code);
    public function resendConfirmationCode(string $username);
    public function initiateAuth(string $username, string $password);
    public function getUser(string $accessToken);
}
