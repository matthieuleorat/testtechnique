<?php

declare(strict_types=1);

namespace App\CommentRate\Command;

class RateCommentCommand
{
    public function __construct(
        private readonly int $id,
        private readonly int $rate,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRate(): int
    {
        return $this->rate;
    }
}
