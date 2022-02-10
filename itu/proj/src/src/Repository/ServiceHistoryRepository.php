<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Repository;

use App\Entity\ServiceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceHistory[]    findAll()
 * @method ServiceHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceHistory::class);
    }

    // /**
    //  * @return ServiceHistory[] Returns an array of ServiceHistory objects
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
    public function findOneBySomeField($value): ?ServiceHistory
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
