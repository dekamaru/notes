<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var Token[]
     * @ORM\OneToMany(targetEntity="App\Entity\Token", cascade={"persist", "remove"}, mappedBy="user", orphanRemoval=true)
     */
    private $tokens;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->tokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function addToken(Token $token): void
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
        }
    }

    public function removeToken(Token $token): void
    {
        if ($this->tokens->contains($token)) {
            $this->tokens->removeElement($token);
        }
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
