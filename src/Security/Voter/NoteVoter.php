<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Note;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class NoteVoter extends Voter
{
    public const PUBLISH = 'publish';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::PUBLISH], true) && $subject instanceof Note;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;

            case self::PUBLISH:
                return $this->canPublish($subject, $user);
                break;

            case self::DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        throw new \LogicException('Should not reach here');
    }

    private function canPublish(Note $note, User $user): bool
    {
        return $this->canEdit($note, $user) && $note->isDraft();
    }

    private function canDelete(Note $note, User $user): bool
    {
        return $this->canEdit($note, $user);
    }

    private function canEdit(Note $note, User $user): bool
    {
        return $note->getAuthor()->getId() === $user->getId();
    }
}
