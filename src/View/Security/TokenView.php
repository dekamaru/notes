<?php

declare(strict_types=1);

namespace App\View\Security;

use App\Entity\Token;

final class TokenView
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTimeImmutable
     */
    private $issuedAt;

    /**
     * @var int
     */
    private $ttl;

    private function __construct(string $token, \DateTimeImmutable $issuedAt, int $ttl)
    {
        $this->token = $token;
        $this->issuedAt = $issuedAt;
        $this->ttl = $ttl;
    }

    public static function from(Token $token): self
    {
        return new self($token->getToken(), $token->getIssuedAt(), $token->getTtl());
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getIssuedAt(): \DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }
}
