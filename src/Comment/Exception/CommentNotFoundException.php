<?php

declare(strict_types=1);

namespace App\Comment\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentNotFoundException extends NotFoundHttpException
{

}
