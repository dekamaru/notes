<?php

declare(strict_types=1);

namespace App\Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class RegistrationRequest
{
    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var string|null
     * @Assert\Length(min="6")
     * @Assert\NotBlank
     */
    private $password;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
