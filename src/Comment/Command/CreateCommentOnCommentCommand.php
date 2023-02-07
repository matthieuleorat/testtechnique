<?php

declare(strict_types=1);

namespace App\Comment\Command;

class CreateCommentOnCommentCommand
{
    public function __construct(private readonly string $content, private readonly int $commentId)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCommentId(): int
    {
        return $this->commentId;
    }
}