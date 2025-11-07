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

    public function countByTypeAndMarque($typeMatos, $marqueMatos): int
    {
        return (int) $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->leftJoin('m.marque_matos', 'mm')
            ->andwhere('m.type_matos= :type_matos')
            ->andwhere('mm.nom_marque= :marque_matos')
            ->setParameter('type_matos', $typeMatos)
            ->setParameter('marque_matos', $marqueMatos)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }


    //SELECTmm.nom_marque AS marque,
    //    SUM(CASE WHEN m.type_matos LIKE 'Ordinateur%' THEN 1 ELSE 0 END) AS Ordinateur,
    //    SUM(CASE WHEN m.type_matos LIKE 'Imprimante%' THEN 1 ELSE 0 END) AS Imprimante,
    //    SUM(CASE WHEN m.type_matos LIKE 'Scanner%' THEN 1 ELSE 0 END) AS Scanner
    //FROM matos_informatique m
    //JOIN marque_matos mm ON m.marque_matos_id = mm.id
    //GROUP BY mm.nom_marque
    //ORDER BY mm.nom_marque;
   //public function countByMarqueAndgroupByType(): array
   //{
   //    return $this->createQueryBuilder('m')
   //        ->select('mm.nom_marque AS marque,
   //            SUM(CASE WHEN m.type_matos = \'Ordinateur Portable%\' THEN 1 ELSE 0 END) AS laptop,
   //            SUM(CASE WHEN m.type_matos = \'Ordinateur Fixe%\' THEN 1 ELSE 0 END) AS desktop,
   //            SUM(CASE WHEN m.type_matos LIKE \'Imprimante%\' THEN 1 ELSE 0 END) AS printer,
   //            SUM(CASE WHEN m.type_matos LIKE \'Scanner%\' THEN 1 ELSE 0 END) AS scanner')
   //        ->leftJoin('m.marque_matos', 'mm')
   //        ->groupBy('mm.nom_marque')
   //        ->orderBy('mm.nom_marque', 'ASC')
   //        ->getQuery()
   //        ->getArrayResult()
   //        ;
   //}

    public function countByMarqueAndGroupByType($type): mixed
    {
        return $this->createQueryBuilder('m')
            ->select('m.type_matos AS type_matos, COUNT(m.id) AS total')
            ->leftJoin('m.marque_matos', 'mm')
            ->andwhere('m.type_matos= :type_matos')
            ->setParameter('type_matos', $type)
            ->groupBy('m.type_matos')
            ->orderBy('m.type_matos', 'ASC')
            ->getQuery()
            ->getScalarResult()
        ;
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
