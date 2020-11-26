<?php

namespace App\Repository;

use App\Entity\Adress;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function getCustomersWithAdress(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT * FROM customer INNER JOIN adress WHERE customer.id = adress.customer_id AND adress.adresstype = "STAMM"'
        );

        return $query->getArrayResult();
    }

    public function getCust($id){
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getArrayResult()
            ;
    }

    public function getLastImportedId(){
        return $this->createQueryBuilder('c')
            ->select('MAX(c.oldId)')
            ->getQuery();
    }
    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
