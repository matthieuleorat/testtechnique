<?php

declare(strict_types=1);

namespace App\Comment;

use App\Article\Exception\ArticleNotFoundException;
use App\Comment\Command\CreateCommentOnArticleCommand;
use App\Comment\Command\CreateCommentOnCommentCommand;
use App\Comment\Command\DeleteCommentCommand;
use App\Comment\Command\EditCommentCommand;
use App\Comment\Command\SetApprovalCommentCommand;
use App\Comment\Exception\CommentNotFoundException;
use App\Comment\Query\ListCommentFromArticleQuery;
use App\Comment\Query\ListCommentFromCommentQuery;
use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Security $security,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function createOnArticle(CreateCommentOnArticleCommand $createCommentOnArticleCommand): Comment
    {
        // Does the referenced article exist
        $article = $this->em->getRepository(Article::class)->findOneBy(
            [
                'id' => $createCommentOnArticleCommand->getArticleId()
            ]
        );

        if (null ==$article) {
            throw new ArticleNotFoundException();
        }

        $comment = new Comment();
        $comment->setContent($createCommentOnArticleCommand->getContent());
        $comment->setArticle($article);

        $user = $this->security->getUser();
        $comment->setPostedBy($user);

        $errors = $this->validator->validate($comment);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    public function createOnComment(CreateCommentOnCommentCommand $createCommentOnCommentCommand): Comment
    {
        // Does the referenced comment exist
        $commentSource = $this->em->getRepository(Comment::class)->findOneBy(
            [
                'id' => $createCommentOnCommentCommand->getCommentId()
            ]
        );

        if (null === $commentSource) {
            throw new CommentNotFoundException();
        }

        $comment = new Comment();
        $comment->setContent($createCommentOnCommentCommand->getContent());
        $comment->setComment($commentSource);

        $user = $this->security->getUser();
        $comment->setPostedBy($user);

        $errors = $this->validator->validate($comment);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    public function setApproval(SetApprovalCommentCommand $approvalCommentCommand): Comment
    {
        $comment = $this->em->getRepository(Comment::class)->findOneBy(['id' => $approvalCommentCommand->getId()]);

        if (null === $comment) {
            throw new CommentNotFoundException();
        }

        $comment->setApproval($approvalCommentCommand->isApproval());

        $this->em->flush();

        return $comment;
    }

    public function delete(DeleteCommentCommand $deleteCommentCommand): bool
    {
        $comment = $this->em->getRepository(Comment::class)->findOneBy(['id' => $deleteCommentCommand->getId()]);

        if (null === $comment) {
            throw new CommentNotFoundException();
        }

        $user = $this->security->getUser();
        if ($user->hasRole('ROLE_ADMIN') || $comment->getPostedBy() === $user && $user->hasRole('ROLE_MEMBER')) {
            $this->em->remove($comment);
            $this->em->flush();

            return true;
        }

        throw new UnauthorizedHttpException('You cannot delete this comment');
    }

    public function listFromComment(ListCommentFromCommentQuery $listCommentFromCommentQuery): array
    {
        return $this->em->getRepository(Comment::class)->findACommentPaginatedForComment($listCommentFromCommentQuery);
    }

    public function listFromArticle(ListCommentFromArticleQuery $listCommentFromArticleQuery): array
    {
        return $this->em->getRepository(Comment::class)->findACommentPaginatedForArticle($listCommentFromArticleQuery);
    }

    public function edit(EditCommentCommand $editCommentCommand): Comment
    {
        $user = $this->security->getUser();
        $comment = $this->em->getRepository(Comment::class)->findOneBy(
            [
                'id' => $editCommentCommand->getId(),
                'postedBy' => $user,
            ]
        );

        if (null === $comment) {
            throw new CommentNotFoundException();
        }

        $comment->setContent($editCommentCommand->getContent());

        $errors = $this->validator->validate($comment);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $this->em->flush();

        return $comment;
    }
}
