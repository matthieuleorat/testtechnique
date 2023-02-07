<?php

declare(strict_types=1);

namespace App\Comment\Command;

class SetApprovalCommentCommand
{
    public function __construct(private readonly int $id, private readonly bool $approval)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isApproval(): bool
    {
        return $this->approval;
    }
}
