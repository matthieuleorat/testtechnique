<?php

declare(strict_types=1);

namespace App\Comment\Command;

class EditCommentCommand
{
    public function __construct(
        private readonly int $id,
        private readonly string $content,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
