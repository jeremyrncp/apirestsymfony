<?php

namespace App\Repository;

use App\Entity\BusinessCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method BusinessCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessCustomer[]    findAll()
 * @method BusinessCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessCustomerRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BusinessCustomer::class);
    }

    public function loadUserByUsername($username)
    {
        return $this->findOneBy(
            ['username' => $username]
        );
    }
}
