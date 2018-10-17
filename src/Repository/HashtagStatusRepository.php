<?php

namespace App\Repository;

use App\Entity\HashtagStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HashtagStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method HashtagStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method HashtagStatus[]    findAll()
 * @method HashtagStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashtagStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HashtagStatus::class);
    }

//    /**
//     * @return HashtagStatus[] Returns an array of HashtagStatus objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HashtagStatus
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
