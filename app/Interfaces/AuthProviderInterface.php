<?php

namespace App\Interfaces;

interface AuthProviderInterface
{
    public function signUp(array $data);
}
