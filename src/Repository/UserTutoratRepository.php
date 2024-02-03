<?php

namespace App\Repository;

use App\Entity\UserTutorat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTutorat>
 *
 * @method UserTutorat|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTutorat|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTutorat[]    findAll()
 * @method UserTutorat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTutoratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTutorat::class);
    }

//    /**
//     * @return UserTutorat[] Returns an array of UserTutorat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserTutorat
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
