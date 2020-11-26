<?php

namespace App\Repository;

use App\Entity\DeliveryPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliveryPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryPlace[]    findAll()
 * @method DeliveryPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryPlace::class);
    }


    public function getKdls($id){
        return $this->createQueryBuilder('d')
            ->andWhere('d.customer = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function getSingleKdl($id){
        return $this->createQueryBuilder('d')
            ->andWhere('d.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    // /**
    //  * @return DeliveryPlace[] Returns an array of DeliveryPlace objects
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
    public function findOneBySomeField($value): ?DeliveryPlace
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
