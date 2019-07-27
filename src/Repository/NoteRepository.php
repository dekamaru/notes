<?php

namespace App\Repository;

use App\Entity\Note;
use App\Model\Request\NoteRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function create(NoteRequest $request): Note
    {
        $note = new Note($request->getAuthor(), $request->getTitle(), $request->getContent());
        $this->getEntityManager()->persist($note);
        $this->getEntityManager()->flush();

        return $note;
    }

    public function update(Note $note, NoteRequest $noteEditRequest): void
    {
        $note->setTitle($noteEditRequest->getTitle());
        $note->setContent($noteEditRequest->getContent());

        $this->getEntityManager()->flush();
    }

    public function delete(Note $note): void
    {
        $this->getEntityManager()->remove($note);
        $this->getEntityManager()->flush();
    }

    public function publish(Note $note): void
    {
        if ($note->isPublished()) {
            throw new \LogicException('Note already published');
        }

        $note->publish();
        $this->getEntityManager()->flush();
    }
}
