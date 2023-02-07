<?php

declare(strict_types=1);

namespace App\Article\Command;

class DeleteCommand
{
    public function __construct(
        private readonly int $id,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
