<?php

declare(strict_types=1);

namespace App\User\Command;

class CreateUserCommand
{
    public function __construct(
        private readonly string $username,
        private readonly string $password,
        private readonly string $role,
    ) {

    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
