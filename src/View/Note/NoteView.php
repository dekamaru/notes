<?php

declare(strict_types=1);

namespace App\View\Note;

use App\Entity\Note;
use App\Entity\User;
use App\View\AuthorView;
use Swagger\Annotations as SWG;

final class NoteView
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var AuthorView
     */
    private $author;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     * @SWG\Property(enum=Note::AVAILABLE_STATUSES)
     */
    private $status;

    /**
     * @var \DateTimeImmutable|null
     */
    private $publishedAt;

    /**
     * @var \DateTimeImmutable
     */
    private $modifiedAt;

    private function __construct(
        int $id,
        User $author,
        string $title,
        string $content,
        string $status,
        ?\DateTimeImmutable $publishedAt,
        ?\DateTimeImmutable $modifiedAt
    ) {
        $this->id = $id;
        $this->author = AuthorView::from($author);
        $this->title = $title;
        $this->content = $content;
        $this->status = $status;
        $this->publishedAt = $publishedAt;
        $this->modifiedAt = $modifiedAt;
    }

    public static function from(Note $note): self
    {
        return new self(
            $note->getId(),
            $note->getAuthor(),
            $note->getTitle(),
            $note->getContent(),
            $note->getStatus(),
            $note->getPublishedAt(),
            $note->getModifiedAt()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): AuthorView
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getModifiedAt(): \DateTimeImmutable
    {
        return $this->modifiedAt;
    }
}
