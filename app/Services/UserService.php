<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(private readonly User $user)
    {
    }

    public function store(string $email, string $cognito_sub)
    {
        $this->user->create([
            'email'       => $email,
            'cognito_sub' => $cognito_sub,
        ]);
    }
}
