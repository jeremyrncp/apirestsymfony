<?php

namespace App\Repository;

use App\Entity\BusinessCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BusinessCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessCustomer[]    findAll()
 * @method BusinessCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessCustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BusinessCustomer::class);
    }

    // /**
    //  * @return BusinessCustomer[] Returns an array of BusinessCustomer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BusinessCustomer
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
