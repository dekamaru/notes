<?php

declare(strict_types=1);

namespace App\View\Note;

use App\Entity\Note;
use App\Entity\User;
use App\View\AuthorView;
use Swagger\Annotations as SWG;

final class NoteListView
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
     * @SWG\Property(enum=Note::AVAILABLE_STATUSES)
     */
    private $status;

    /**
     * @var \DateTimeImmutable|null
     */
    private $publishedAt;

    private function __construct(int $id, User $author, string $title, string $status, ?\DateTimeImmutable $publishedAt)
    {
        $this->id = $id;
        $this->author = AuthorView::from($author);
        $this->title = $title;
        $this->status = $status;
        $this->publishedAt = $publishedAt;
    }

    public static function from(Note $note): self
    {
        return new self(
            $note->getId(),
            $note->getAuthor(),
            $note->getTitle(),
            $note->getStatus(),
            $note->getPublishedAt()
        );
    }

    /**
     * @param NoteListView[] $notes
     *
     * @return self[]
     */
    public static function fromCollection(array $notes): array
    {
        return array_map(function (Note $note) {
            return self::from($note);
        }, $notes);
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
