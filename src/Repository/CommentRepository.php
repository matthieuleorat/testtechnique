<?php

namespace App\Repository;

use App\Comment\Query\ListCommentFromArticleQuery;
use App\Comment\Query\ListCommentFromCommentQuery;
use App\CommentRate\Command\RateCommentCommand;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findACommentPaginatedForComment(ListCommentFromCommentQuery $listQuery): array
    {
        $pageSize = $listQuery->getArticlesPerPage();

        $firstResult = ($listQuery->getPage() - 1) * $pageSize;

        $query =  $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->where('c.comment = :commentId')
            ->setParameter('commentId', $listQuery->getId())
            ->setMaxResults($pageSize)
            ->setFirstResult($firstResult)
            ->getQuery()
        ;

        return $query->getResult();
    }

    public function findACommentPaginatedForArticle(ListCommentFromArticleQuery $listQuery): array
    {
        $pageSize = $listQuery->getArticlesPerPage();

        $firstResult = ($listQuery->getPage() - 1) * $pageSize;

        $query =  $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->where('c.article = :articleId')
            ->setParameter('articleId', $listQuery->getId())
            ->setMaxResults($pageSize)
            ->setFirstResult($firstResult)
            ->getQuery()
        ;

        return $query->getResult();
    }

    public function findNotOwnedComment(RateCommentCommand $rateCommentCommand, UserInterface $user): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->andWhere('c.postedBy != :user')
            ->setParameter('id', $rateCommentCommand->getId())
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
