<?php

declare(strict_types=1);

namespace App\Exception\User;

final class UserRegistrationException extends \Exception
{
    public static function usernameAlreadyExists(): self
    {
        return new self('User with this username already exists');
    }
}
