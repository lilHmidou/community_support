<?php

namespace App\Repository;

use App\Entity\ConfigModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigModule>
 *
 * @method ConfigModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigModule[]    findAll()
 * @method ConfigModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigModule::class);
    }

//    /**
//     * @return ConfigModule[] Returns an array of ConfigModule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConfigModule
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
