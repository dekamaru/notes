<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column
     */
    private $token;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $issuedAt;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $ttl;

    public function __construct(User $user, int $ttl)
    {
        $this->user = $user;
        $this->issuedAt = new \DateTimeImmutable();
        $this->ttl = $ttl;
        $this->token = bin2hex(random_bytes(16));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
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

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();
        $liveUntil = (new \DateTimeImmutable())->add(new \DateInterval(sprintf('PT%dS', $this->ttl)));

        return $now > $liveUntil;
    }

    public function __toString()
    {
        return $this->token;
    }
}
