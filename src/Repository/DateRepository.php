<?php

namespace App\Repository;

use App\Entity\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Date|null find($id, $lockMode = null, $lockVersion = null)
 * @method Date|null findOneBy(array $criteria, array $orderBy = null)
 * @method Date[]    findAll()
 * @method Date[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateRepository extends ServiceEntityRepository
{

    private $dateNow;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Date::class);

        $this->dateNow = new \DateTime();
    }

    /**
    * @return Date[] Returns an array of Date objects
    */

    public function getAvailableDates($vehicle)
    {
        $dates = $this->createQueryBuilder('d')
            ->andWhere('d.vehicle = :vehicle')
            ->setParameter('vehicle', $vehicle)
            ->andWhere('d.availableDate >= :date')
            ->setParameter('date', $this->dateNow)
            ->orderBy('d.availableDate')
            ->getQuery()
            ->getResult()
        ;


        return $dates;
    }

 

    /*
    public function findOneBySomeField($value): ?Date
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
