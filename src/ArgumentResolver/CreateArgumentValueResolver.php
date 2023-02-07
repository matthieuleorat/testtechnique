<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Article\Command\CreateCommand;
use App\Comment\Command\CreateCommentOnArticleCommand;
use App\Comment\Command\CreateCommentOnCommentCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class CreateArgumentValueResolver implements ArgumentValueResolverInterface
{
    const ALLOWED_TYPES = [
        CreateCommand::class,
        CreateCommentOnArticleCommand::class,
        CreateCommentOnCommentCommand::class,
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
        return $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
    }
}
