<?php

declare(strict_types=1);

namespace App\Article\Command;

class EditCommand
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
