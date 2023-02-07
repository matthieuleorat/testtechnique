<?php

declare(strict_types=1);

namespace App\Article\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleNotFoundException extends NotFoundHttpException
{

}
