<?php

namespace App\Repository;

use App\Entity\ImageName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageName[]    findAll()
 * @method ImageName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageName::class);
    }

    // /**
    //  * @return ImageName[] Returns an array of ImageName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageName
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
