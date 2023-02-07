<?php

declare(strict_types=1);

namespace App\Controller;

use App\Comment\Command\CreateCommentOnArticleCommand;
use App\Comment\Command\CreateCommentOnCommentCommand;
use App\Comment\Command\DeleteCommentCommand;
use App\Comment\Command\EditCommentCommand;
use App\Comment\Command\SetApprovalCommentCommand;
use App\Comment\CommentService;
use App\Comment\Query\ListCommentFromArticleQuery;
use App\Comment\Query\ListCommentFromCommentQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    public function __construct(private readonly CommentService $commentService)
    {
    }

    #[Route('/article/create', name: 'create_comment_on_article', methods: ['POST'])]
    public function createOnArticle(CreateCommentOnArticleCommand $createCommentOnArticleCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $comment = $this->commentService->createOnArticle($createCommentOnArticleCommand);

        return $this->json($comment);
    }

    #[Route('/comment/create', name: 'create_comment_on_comment', methods: ['POST'])]
    public function createOnComment(CreateCommentOnCommentCommand $createCommentOnCommentCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $comment = $this->commentService->createOnComment($createCommentOnCommentCommand);

        return $this->json(
            $comment
        );
    }

    #[Route('/approval/{id<\d+>}', name: 'comment_approval', methods: ['PUT'])]
    public function setApproval(SetApprovalCommentCommand $approvalCommentCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $comment = $this->commentService->setApproval($approvalCommentCommand);

        return $this->json(
            $comment
        );
    }

    #[Route('/delete/{id<\d+>}', name: 'comment_delete', methods: ['DELETE'])]
    public function delete(DeleteCommentCommand $deleteCommentCommand): Response
    {
        // For this case, authorization is handled inside the service, because the behavior is diffefent regarding the user role
        $result = $this->commentService->delete($deleteCommentCommand);

        return $this->json(
            $result
        );
    }

    #[Route('/article/{id<\d+>}/{page<\d+>?1}', name: 'list_comments_from_article', methods: ['GET'], stateless: true)]

    public function listFromArticle(ListCommentFromArticleQuery $listCommentFromArticleQuery): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $comments = $this->commentService->listFromArticle($listCommentFromArticleQuery);

        $response =  $this->json(
            $comments
        );

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/comment/{id<\d+>}/{page<\d+>?1}', name: 'list_comments_from_comment', methods: ['GET'], stateless: true)]
    public function listFromComment(ListCommentFromCommentQuery $listCommentFromCommentQuery): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $comments = $this->commentService->listFromComment($listCommentFromCommentQuery);

        $response =  $this->json(
            $comments
        );

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    #[Route('/edit/{id<\d+>}', name: 'comment_edit', methods: ['PUT'])]
    public function edit(EditCommentCommand $editCommentCommand)
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $comment = $this->commentService->edit($editCommentCommand);

        return $this->json($comment);
    }
}
