<?php

declare(strict_types=1);

namespace App\Controller;

use App\Article\ArticleService;
use App\Article\Command\CreateCommand;
use App\Article\Command\DeleteCommand;
use App\Article\Command\EditCommand;
use App\Article\Query\ListQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/article', name: 'article_')]
class ArticleController extends AbstractController
{
    public function __construct(
        readonly private ArticleService $articleService,
    ) {
    }

    #[Route(
        '/create',
        name: 'create',
        methods: ['POST']
    )]
    public function new(CreateCommand $createCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $article = $this->articleService->create($createCommand);

        return $this->json($article);
    }

    #[Route(
        '/edit/{id<\d+>}',
        name: 'edit',
        methods: ['PUT']
    )]
    public function edit(EditCommand $editCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $article = $this->articleService->edit($editCommand);

        return $this->json($article);
    }

    #[Route(
        '/delete/{id<\d+>}',
        name: 'delete',
        methods: ['DELETE']
    )]
    public function delete(DeleteCommand $deleteCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $article = $this->articleService->delete($deleteCommand);

        return $this->json($article);
    }

    #[Route(
        '/{page<\d+>?1}',
        name: 'list',
        methods: ['GET'],
        stateless: true
    )]
    public function list(ListQuery $listQuery): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $articles = $this->articleService->list($listQuery);

        $response =  $this->json(
            $articles
        );

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}
