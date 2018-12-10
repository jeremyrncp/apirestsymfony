<?php

namespace App\Repository;

use App\Entity\PhoneCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhoneCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneCategory[]    findAll()
 * @method PhoneCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhoneCategory::class);
    }

    // /**
    //  * @return PhoneCategory[] Returns an array of PhoneCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhoneCategory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
