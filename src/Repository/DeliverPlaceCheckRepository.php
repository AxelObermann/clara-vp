<?php

namespace App\Repository;

use App\Entity\DeliverPlaceCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliverPlaceCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliverPlaceCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliverPlaceCheck[]    findAll()
 * @method DeliverPlaceCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliverPlaceCheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliverPlaceCheck::class);
    }

    // /**
    //  * @return DeliverPlaceCheck[] Returns an array of DeliverPlaceCheck objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeliverPlaceCheck
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
