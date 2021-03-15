<?php

namespace App\Repository;

use App\Entity\IpRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IpRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpRequest[]    findAll()
 * @method IpRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpRequest::class);
    }

    public function getLastIpByPath($path, $ip): ?IpRequest
    {
        return $this->createQueryBuilder('i')
          ->where('i.ip = :ip')
          ->andWhere('i.path = :path')
          ->setParameter('ip', $ip)
          ->setParameter('path', $path)
          ->orderBy('i.timestamp', 'DESC')
          ->setMaxResults(1)
          ->getQuery()
          ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return IpRequest[] Returns an array of IpRequest objects
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
    public function findOneBySomeField($value): ?IpRequest
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
