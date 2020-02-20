<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\SearchFilter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{

    private $dateTime;

    public function __construct(ManagerRegistry $registry)
    {
        $this->dateTime = new \DateTime();
        parent::__construct($registry, Vehicle::class);
    }

    /**
    * @return Vehicle[] Returns an array of Vehicle objects
    */
    public function findByFilters(SearchFilter $searchFilter) {

        // All vehicule by status AND minimum one available date
        $sql = '
        SELECT v
        FROM App\Entity\Vehicle v
        WHERE EXISTS(SELECT d
            FROM App\Entity\Date d
            WHERE d.vehicle = v.id
            AND d.availableDate > :date)
                AND v.status = :status
            ';

        $parameters['date'] = $this->dateTime;
        $parameters['status'] = 1;

        if ($searchFilter->getBrand()) {
                    $sql = $sql . ' AND v.brand LIKE :brand';
                    $parameters['brand'] = '%'.$searchFilter->getBrand().'%';
        }

        if ($searchFilter->getModel()) {
                    $sql = $sql . ' AND v.model LIKE :model';
                    $parameters['model'] = '%'.$searchFilter->getModel().'%';
        }

        if ($searchFilter->getSeatNumber()) {
                    $sql = $sql . ' AND v.seatNumber >= :seat';
                    $parameters['seat'] = $searchFilter->getSeatNumber();
        }

        if ($searchFilter->getMaxSpeed()) {
                    $sql = $sql . ' AND v.maxSpeed >= :speed';
                    $parameters['speed'] = $searchFilter->getMaxSpeed();
        }

        if ($searchFilter->getWeight()) {
                    $sql = $sql . ' AND v.weight <= :weight';
                    $parameters['weight'] = $searchFilter->getWeight();
        }

        if ($searchFilter->getPower()) {
                    $sql = $sql . ' AND v.power >= :power';
                    $parameters['power'] = $searchFilter->getPower(); 
        }

        if ($searchFilter->getAutonomy()) {
                    $sql =  $sql . ' AND v.autonomy >= :autonomy';
                    $parameters['autonomy'] = $searchFilter->getAutonomy();  
        }

        if ($searchFilter->getDayCost()) {
                    $sql = $sql . ' AND v.dayCost <= :price';
                    $parameters['price'] = $searchFilter->getDayCost();  
        }

        if ($searchFilter->getType()) {
                    $sql = $sql .' AND v.type IN (:type)';
                    $parameters['type'] = $searchFilter->getType();  
        }

        $sql = $sql . ' ORDER BY v.createdAt DESC';

        $entityManager = $this->getEntityManager();

        $vehicles = $entityManager->createQuery(
            $sql
        )->setParameters($parameters);

        return $vehicles->getResult();
    }

    
    public function findByAvailableDates()
    {

        $vehicles = $this->createQueryBuilder('v')
            ->andWhere('v.status = 1')
            ->orderBy('v.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        $VehiclesMatch = [];

        foreach ($vehicles as $vehicle) {

            foreach ($vehicle->getDates() as $date) {
                if ($date->getAvailableDate() >= $this->dateTime) {
                    $VehiclesMatch[] = $vehicle;
                    break;
                }
            }

        }
        
        return $VehiclesMatch;

    }

    public function getLastVehicles()
    {

        $vehicles = $this->createQueryBuilder('v')
            ->andWhere('v.status = 1')
            ->orderBy('v.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        $VehiclesMatch = [];

        foreach ($vehicles as $vehicle) {

            if (count($VehiclesMatch) == 4 ){

                return $VehiclesMatch;
            }

            foreach ($vehicle->getDates() as $date) {
                if ($date->getAvailableDate() >= $this->dateTime) {
                    $VehiclesMatch[] = $vehicle;
                    break;
                }
            }
            
        

        } 
        return $VehiclesMatch;

    }
}