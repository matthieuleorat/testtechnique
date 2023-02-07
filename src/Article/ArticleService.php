<?php

declare(strict_types=1);

namespace App\Article;

use App\Article\Command\CreateCommand;
use App\Article\Command\DeleteCommand;
use App\Article\Command\EditCommand;
use App\Article\Exception\ArticleNotFoundException;
use App\Article\Query\ListQuery;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleService
{
    public function __construct(
      readonly private EntityManagerInterface $em,
      private readonly ValidatorInterface $validator,
    ) {
    }

    public function list(ListQuery $listQuery): array
    {
        return $this->em->getRepository(Article::class)->findArticlesPaginated($listQuery);
    }

    public function create(CreateCommand $createCommand): Article
    {
        $article = new Article();
        $article->setTitle($createCommand->getTitle());

        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }

    public function edit(EditCommand $editCommand): Article
    {
        $article = $this->em->getRepository(Article::class)->findOneBy(['id' => $editCommand->getId()]);

        if (null === $article) {
            throw new ArticleNotFoundException("Article {$editCommand->getId()} not found");
        }

        $article->setTitle($editCommand->getTitle());

        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }
        
        $this->em->flush();

        return $article;
    }

    public function delete(DeleteCommand $deleteCommand): bool
    {
        $article = $this->em->getRepository(Article::class)->findOneBy(['id' => $deleteCommand->getId()]);

        if (null === $article) {
            throw new ArticleNotFoundException("Article {$deleteCommand->getId()} not found");
        }

        $this->em->remove($article);
        $this->em->flush();

        return true;
    }
}
