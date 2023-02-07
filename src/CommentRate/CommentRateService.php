<?php

declare(strict_types=1);

namespace App\CommentRate;

use App\Comment\Exception\CommentNotFoundException;
use App\CommentRate\Command\RateCommentCommand;
use App\Entity\Comment;
use App\Entity\CommentRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentRateService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function rate(RateCommentCommand $rateCommentCommand): CommentRate
    {
        $user = $this->security->getUser();

        $comment = $this->em->getRepository(Comment::class)->findNotOwnedComment($rateCommentCommand, $user);

        if (null === $comment) {
            throw new CommentNotFoundException();
        }

        // Is rate already set
        $commentRate = $this->em->getRepository(CommentRate::class)->findOneby(
            [
                'comment' => $comment,
                'ratedBy' => $user,
            ]
        );

        if (null === $commentRate) {
            $commentRate = new CommentRate();
            $commentRate->setRatedBy($user);
            $commentRate->setComment($comment);
        }

        $commentRate->setRate($rateCommentCommand->getRate());

        $errors = $this->validator->validate($commentRate);

        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $this->em->persist($commentRate);
        $this->em->flush();

        return $commentRate;
    }
}
