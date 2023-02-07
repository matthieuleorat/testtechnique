<?php

declare(strict_types=1);

namespace App\User;

use App\Entity\User;
use App\User\Command\ChangePasswordCommand;
use App\User\Command\CreateUserCommand;
use App\User\Exception\UsernameUsedException;
use App\User\Exception\UserNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {}

    public function createUser(CreateUserCommand $createUserCommand): void
    {
        try {
            $user = new User();
            $user->setUsername($createUserCommand->getUsername());
            $user->setRoles([$createUserCommand->getRole()]);

            $plaintextPassword = $createUserCommand->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new UsernameUsedException("The username {$createUserCommand->getUsername()} is already taken");
        }
    }

    public function changePassword(ChangePasswordCommand $changePasswordCommand): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $changePasswordCommand->getUsername()]);

        if (null === $user) {
            throw new UserNotFoundException("User {$changePasswordCommand->getUsername()} not found");
        }

        $plaintextPassword = $changePasswordCommand->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $this->em->flush();
    }
}
