<?php

namespace App\Controller;

use App\CommentRate\Command\RateCommentCommand;
use App\CommentRate\CommentRateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comment_rate', name: 'comment_rate_')]
class CommentRateController extends AbstractController
{
    public function __construct(private readonly CommentRateService $commentRateService)
    {
    }

    #[Route('/rate/{id<\d+>}', name: 'rate', methods: ['POST'])]
    public function rate(RateCommentCommand $rateCommentCommand): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');

        $commentRate = $this->commentRateService->rate($rateCommentCommand);

        return $this->json($commentRate);
    }
}
