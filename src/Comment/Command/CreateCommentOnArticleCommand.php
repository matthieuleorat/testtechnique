<?php

declare(strict_types=1);

namespace App\Comment\Command;

class CreateCommentOnArticleCommand
{
    public function __construct(private readonly string $content, private readonly int $articleId)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }


}
