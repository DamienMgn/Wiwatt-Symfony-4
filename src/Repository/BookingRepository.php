<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
    * @return Booking[] Returns an array of Booking objects
    */
    
    public function findByOwner($owner)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
        ;
    }

    /**
    * @return Booking[] Returns an array of Booking objects
    */
    
    public function findByRenter($renter)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.renter = :renter')
            ->setParameter('renter', $renter)
            ->orderBy('b.createdAt', 'DESC')
            ->getQuery()
        ;
    }
}
