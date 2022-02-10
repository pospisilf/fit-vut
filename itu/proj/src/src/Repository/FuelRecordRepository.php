<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Repository;

use App\Entity\FuelRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FuelRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelRecord[]    findAll()
 * @method FuelRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FuelRecord::class);
    }

    public function findByVehicleId($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.vehicle = :val')
            ->setParameter('val', $value)
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByVehicleIdMax3($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.vehicle = :val')
            ->setParameter('val', $value)
            ->orderBy('f.date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

}
