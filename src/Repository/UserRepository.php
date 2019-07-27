<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserRegistrationException;
use App\Model\Request\RegistrationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(RegistryInterface $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $encoder;
    }

    /**
     * @param RegistrationRequest $request
     *
     * @throws UserRegistrationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(RegistrationRequest $request)
    {
        $existingUser = $this->findOneBy(['username' => $request->getUsername()]);
        if (null !== $existingUser) {
            throw UserRegistrationException::usernameAlreadyExists();
        }

        $user = new User($request->getUsername());
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $request->getPassword());
        $user->setPassword($encodedPassword);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
