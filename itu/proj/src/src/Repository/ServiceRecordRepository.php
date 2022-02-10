<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Repository;

use App\Entity\ServiceRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceRecord[]    findAll()
 * @method ServiceRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceRecord::class);
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
