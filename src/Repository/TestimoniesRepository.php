<?php

namespace App\Repository;

use App\Entity\Testimonies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonies>
 *
 * @method Testimonies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testimonies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testimonies[]    findAll()
 * @method Testimonies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimoniesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonies::class);
    }

//    /**
//     * @return Testimonies[] Returns an array of Testimonies objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Testimonies
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
