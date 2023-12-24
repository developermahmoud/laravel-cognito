<?php

namespace App\Interfaces;

interface AuthProviderInterface
{
    public function signUp(array $data): string;
    public function confirmSignUp(string $username, string $code);
    public function resendConfirmationCode(string $username);
}
