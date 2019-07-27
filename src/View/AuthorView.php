<?php

declare(strict_types=1);

namespace App\View;

use App\Entity\User;

final class AuthorView
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    private function __construct(int $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public static function from(User $author): self
    {
        return new self($author->getId(), $author->getUsername());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
