<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserAuthenticationProvider implements UserAuthenticationProviderInterface
{
    /**
     * @var int
     */
    private $tokenTtl;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(int $tokenTtl, EntityManagerInterface $entityManager)
    {
        $this->tokenTtl = $tokenTtl;
        $this->entityManager = $entityManager;
    }

    public function findUserByToken(string $token): ?UserInterface
    {
        $token = $this->findTokenByHash($token);
        if (null === $token) {
            return null;
        }

        if ($token->isExpired()) {
            $token->getUser()->removeToken($token);
            $this->entityManager->flush();

            return null;
        }

        return $token->getUser();
    }

    public function createToken(User $user): Token
    {
        $token = new Token($user, $this->tokenTtl);
        $user->addToken($token);

        $this->entityManager->flush();

        return $token;
    }

    private function findTokenByHash(string $hash): ?Token
    {
        $queryBuilder = $this->entityManager->createQueryBuilder('token');
        $queryBuilder
            ->select('token')
            ->from(Token::class, 'token')
            ->andWhere($queryBuilder->expr()->eq('token.token', ':token'))
            ->setParameter('token', $hash)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
