<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use App\Entity\Token;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserAuthenticationProviderInterface
{
    public function findUserByToken(string $token): ?UserInterface;

    public function createToken(User $user): Token;
}
