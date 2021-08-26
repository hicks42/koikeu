<?php

namespace App\Repository;

use App\Entity\Prev;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prev|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prev|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prev[]    findAll()
 * @method Prev[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrevRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prev::class);
    }

    // /**
    //  * @return Prev[] Returns an array of Prev objects
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
    public function findOneBySomeField($value): ?Prev
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
