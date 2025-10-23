<?php

namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Affectation;

/**
 * @extends ServiceEntityRepository<Agent>
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    public function searchdoublonsAgentPrenomNomCount(string $prenom, string $nom): int
    {
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->join('a.identification', 'i')
            ->where('i.prenom = :prenom')
            ->andWhere('i.nom = :nom')
            ->setParameter('prenom', $prenom)
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countBySexe(string $sexe): int
    {
        return  (int)$this->createQueryBuilder('a')
            ->select('COUNT(a.id) as count')
            ->join('a.identification', 'i')
            ->where('i.sexe = :sexe')
            ->setParameter('sexe', $sexe)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function evolutionAgent(): mixed
    {
        return $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.date_recrutement,1,4) AS date_record, COUNT(a.id) AS nb_recrus')
            ->groupBy('date_record')
            ->getQuery()
            ->getResult()
        ;
    }
    public function evolutionAgentBySexe($sexe): mixed
    {
        return $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.date_recrutement,1,4) AS date_record, COUNT(a.id) AS nb_recrus')
            ->leftJoin('a.identification', 'i')
            ->andWhere('i.sexe = :sexe')
            ->groupBy('date_record')
            ->setParameter('sexe',$sexe)
            ->getQuery()
            ->getResult()
        ;
    }




    //    /**
    //     * @return Agent[] Returns an array of Agent objects
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

    //    public function findOneBySomeField($value): ?Agent
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
