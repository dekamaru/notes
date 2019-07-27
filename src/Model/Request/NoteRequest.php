<?php

declare(strict_types=1);

namespace App\Model\Request;

use App\Entity\Note;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class NoteRequest
{
    /**
     * @var User
     */
    private $author;

    /**
     * @var string|null
     * @Assert\NotBlank
     * @Assert\Length(max=1024)
     */
    private $title;

    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private $content;

    public function __construct(User $author)
    {
        $this->author = $author;
    }

    public static function fromNote(Note $note): self
    {
        $request = new self($note->getAuthor());
        $request->setTitle($note->getTitle());
        $request->setContent($note->getContent());

        return $request;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
