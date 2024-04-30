<?php

namespace App\Repository;

use App\Entity\EventParticipation;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventParticipation>
 *
 * @method EventParticipation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipation[]    findAll()
 * @method EventParticipation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipation::class);
    }

    public function findAllByUserId(int $userId): array
    {
        $participations = $this->createQueryBuilder('pl')
            ->select('pl.post_id')
            ->where('pl.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        $postIds = array_column($participations, 'post_id');

        $posts = $this->getEntityManager()
            ->getRepository(Post::class)
            ->findBy(['id' => $postIds]);

        return $posts;
    }

//    /**
//     * @return EventParticipation[] Returns an array of EventParticipation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventParticipation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
