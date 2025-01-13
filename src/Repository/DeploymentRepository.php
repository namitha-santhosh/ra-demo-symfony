<?php

namespace App\Repository;

use App\Entity\Deployment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deployment>
 *
 * @method Deployment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deployment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deployment[]    findAll()
 * @method Deployment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeploymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployment::class);
    }

//    /**
//     * @return Deployment[] Returns an array of Deployment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Deployment
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
