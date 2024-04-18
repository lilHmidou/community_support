<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param int $userId
     * @return Post[]
     */
    public function findAllPostsByUserId(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function updatePost(int $postId, array $data): void
    {
        $entityManager = $this->getEntityManager();
        $post = $entityManager->getRepository(Post::class)->find($postId);


        $post->setTitle($data['title']);

        $post->setDescription($data['description']);

        $entityManager->flush();
    }

    /**
     * @param int $userId
     * @return Post[]
     */


    /**
     * Find the user associated with a given post ID.
     *
     * @param int $postId
     * @return User|null
     */
    public function findUserByPostId(int $postId): ?User
    {
        $entityManager = $this->getEntityManager();
        $post = $entityManager->getRepository(Post::class)->find($postId);

        return $post ? $post->getUser() : null;
    }
//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
