<?php

namespace App\Repository;

use App\Entity\Deployment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeploymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployment::class);
    }

    public function findLatestDeployments(int $limit = 10): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findReleaseDeployments(string $releaseName): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.releaseName = :releaseName')
            ->setParameter('releaseName', $releaseName)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}