<?php

namespace App\Repository;

use App\Entity\Latest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Latest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Latest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Latest[]    findAll()
 * @method Latest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LatestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Latest::class);
    }

//    /**
//     * @return Latest[] Returns an array of Latest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Latest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
