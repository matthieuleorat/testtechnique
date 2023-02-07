<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Article\Command\DeleteCommand;
use App\Article\Command\EditCommand;
use App\Article\Exception\IdsdontMatchException;
use App\Comment\Command\DeleteCommentCommand;
use App\Comment\Command\EditCommentCommand;
use App\Comment\Command\SetApprovalCommentCommand;
use App\CommentRate\Command\RateCommentCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class IdsMatchingArgumentValueResolver implements ArgumentValueResolverInterface
{
    const ALLOWED_TYPES = [
        DeleteCommand::class,
        SetApprovalCommentCommand::class,
        DeleteCommentCommand::class,
        EditCommand::class,
        EditCommentCommand::class,
        RateCommentCommand::class,
    ];

    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return in_array($argument->getType(), self::ALLOWED_TYPES);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->createFromRequest($request, $argument);
    }

    private function createFromRequest(Request $request, ArgumentMetadata $argument)
    {
        $articleAsJson = json_decode($request->getContent());
        if ($articleAsJson->id != $request->attributes->get('id')) {
            throw new IdsdontMatchException('Ids between payload and uri must match');
        }

        return $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
    }
}
