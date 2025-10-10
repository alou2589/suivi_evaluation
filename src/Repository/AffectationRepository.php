<?php

namespace App\Repository;

use App\Entity\Affectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affectation>
 */
class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectation::class);
    }

    public function findWithAgentServiceAndPoste(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.agent', 'ag')
            ->leftJoin('a.service', 's')
            ->leftJoin('a.poste', 'p')
            ->addSelect('ag', 's', 'p')
            ->getQuery()
            ->getResult();
    }

    public function findByDirectionStatutAffectation($direction, $statut_affectation): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.service', 's')
            ->andWhere('s.structure_rattachee = :direction')
            ->andWhere('a.statut_affectation = :statut_affectation')
            ->setParameter('direction', $direction)
            ->setParameter('statut_affectation', $statut_affectation)
            ->getQuery()
            ->getResult();
    }

    public function findAffectationByPoste($poste): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.poste = :poste')
            ->setParameter('poste', $poste)
            ->getQuery()
            ->getResult();
    }

    public function findLastAffectationByAgent($agent): ?Affectation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('a.date_debut', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countSexeByDirection($direction, $sexe): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->leftJoin('a.agent', 'ag')
            ->leftJoin('a.service', 's')
            ->leftJoin('ag.identification', 'i')
            ->andWhere('s.structure_rattachee = :direction')
            ->andWhere('i.sexe = :sexe')
            ->setParameter('direction', $direction)
            ->setParameter('sexe', $sexe)
            ->getQuery()
            ->getSingleScalarResult();
    }


    //    /**
    //     * @return Affectation[] Returns an array of Affectation objects
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

    //    public function findOneBySomeField($value): ?Affectation
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
