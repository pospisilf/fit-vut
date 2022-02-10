<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Repository;

use App\Entity\ServiceOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceOperation[]    findAll()
 * @method ServiceOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceOperation::class);
    }

    // /**
    //  * @return ServiceOperation[] Returns an array of ServiceOperation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ServiceOperation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
