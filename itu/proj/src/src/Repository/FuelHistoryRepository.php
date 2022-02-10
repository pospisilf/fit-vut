<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Repository;

use App\Entity\FuelHistory;
use App\Entity\FuelRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FuelHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelHistory[]    findAll()
 * @method FuelHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FuelHistory::class);
    }

    // /**
    //  * @return FuelHistory[] Returns an array of FuelHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    // public function findFuelHistoryById($value): ?FuelHistory
    // {
    //     return $this->createQueryBuilder('f')
    //         ->andWhere('f.vehicle = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}
