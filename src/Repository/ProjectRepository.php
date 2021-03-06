<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC');
    }

    public function findOneByUser($user, int $maxResults = 10): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.users', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user)
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getProjectsViews(): ?int
    {
      return (int) $this->createQueryBuilder('p')
          ->select('SUM(p.views)')
          ->getQuery()
          ->getSingleScalarResult()
      ;
    }

    public function search($title): array
    {
      return $this->createQueryBuilder('p')
          ->andWhere('p.name LIKE :title')
          ->setParameter('title', '%'.$title.'%')
          ->getQuery()
          ->getResult();
    }
}
