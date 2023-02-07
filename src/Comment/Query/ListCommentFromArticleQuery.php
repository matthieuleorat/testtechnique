<?php

declare(strict_types=1);

namespace App\Comment\Query;

class ListCommentFromArticleQuery
{
    private const ARTICLE_PER_PAGE = 2;

    public function __construct(private readonly int $id, private readonly int $page)
    {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getArticlesPerPage(): int
    {
        return self::ARTICLE_PER_PAGE;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
