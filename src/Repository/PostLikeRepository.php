<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostLike>
 *
 * @method PostLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLike[]    findAll()
 * @method PostLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLike::class);
    }

    public function removeLike(int $user_id, int $post_id): void
    {
        $qb = $this->createQueryBuilder('l');
        $qb->delete()
            ->andWhere('l.user_id = :user_id')
            ->andWhere('l.post_id = :post_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('post_id', $post_id);

        $qb->getQuery()->execute();
    }

    public function findPostsLikedByUser(int $userId): array
    {
        $likes = $this->createQueryBuilder('pl')
            ->select('pl.post_id')
            ->where('pl.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        $postIds = array_column($likes, 'post_id');

        $posts = $this->getEntityManager()
            ->getRepository(Post::class)
            ->findBy(['id' => $postIds]);

        return $posts;
    }
//    /**
//     * @return Like[] Returns an array of Like objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Like
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
