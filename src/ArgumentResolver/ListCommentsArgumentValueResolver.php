<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Article\Query\ListQuery;
use App\Comment\Query\ListCommentFromArticleQuery;
use App\Comment\Query\ListCommentFromCommentQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ListCommentsArgumentValueResolver implements ArgumentValueResolverInterface
{
    const ALLOWED_TYPES = [
        ListCommentFromCommentQuery::class,
        ListCommentFromArticleQuery::class,
    ];

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return in_array($argument->getType(), self::ALLOWED_TYPES);    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->createFromRequest($request, $argument);
    }

    private function createFromRequest(Request $request, ArgumentMetadata $argument)
    {
        $page = (int) $request->attributes->get('page');
        $id = (int) $request->attributes->get('id');

        return new ($argument->getType())($id, $page);
    }
}
