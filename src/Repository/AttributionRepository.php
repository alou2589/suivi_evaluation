<?php

namespace App\Repository;

use App\Entity\Attribution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attribution>
 */
class AttributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attribution::class);
    }

    public function countByTypeMatosInDirection($typeMatos, $direction): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->leftJoin('a.materiel', 'm')
            ->leftJoin('a.affectaire', 'aff')
            ->leftJoin('aff.service', 's')
            ->andWhere('m.type_matos LIKE :typeMatos')
            ->andWhere('s.structure_rattachee = :direction')
            ->setParameter('typeMatos', $typeMatos.'%')
            ->setParameter('direction', $direction)
            ->getQuery()
            ->getSingleScalarResult();
    }



    //    /**
    //     * @return Attribution[] Returns an array of Attribution objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Attribution
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
