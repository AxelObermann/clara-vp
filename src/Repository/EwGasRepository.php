<?php

namespace App\Repository;

use App\Entity\EwGas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EwGas|null find($id, $lockMode = null, $lockVersion = null)
 * @method EwGas|null findOneBy(array $criteria, array $orderBy = null)
 * @method EwGas[]    findAll()
 * @method EwGas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EwGasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EwGas::class);
    }

    // /**
    //  * @return EwGas[] Returns an array of EwGas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EwGas
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
