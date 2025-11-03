<?php

namespace App\Repository;

use App\Entity\MatosInformatique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatosInformatique>
 */
class MatosInformatiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatosInformatique::class);
    }

    public function findDistinctTypeMatos(): array
    {
        return $this->createQueryBuilder('m')
            ->select("DISTINCT m.type_matos AS types")
            ->getQuery()
            ->getResult();
    }


    //public function countByTypeMatos($typeMatos): int
    //{
    //    return (int) $this->createQueryBuilder('m')
    //        ->select('COUNT(m.id)')
    //        ->andWhere('m.type_matos = :typeMatos')
    //        ->setParameter('typeMatos', $typeMatos)
    //        ->getQuery()
    //        ->getSingleScalarResult();
    //}

//    /**
//     * @return MatosInformatique[] Returns an array of MatosInformatique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MatosInformatique
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
