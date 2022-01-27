<?php

namespace App\Repository;

use App\Entity\UserOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOption[]    findAll()
 * @method UserOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOption::class);
    }

    // /**
    //  * @return UserOption[] Returns an array of UserOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserOption
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
