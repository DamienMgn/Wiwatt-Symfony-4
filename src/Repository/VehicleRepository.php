<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\SearchFilter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findByFilters(SearchFilter $searchFilter)
    {
        $vehicles = $this->vehicleFilter($searchFilter);

        return $vehicles;
    }

    private function vehicleFilter($vehicleFilter) {
        // All vehicles by city
        $vehicles = $this->createQueryBuilder('v')
            ->andWhere('v.status = 1')
            ->orderBy('v.createdAt', 'DESC')
        ;

        if ($vehicleFilter->getBrand()) {
            $vehicles = $vehicles
                    ->andWhere('v.brand LIKE :brand')
                    ->setParameter('brand', '%'.$vehicleFilter->getBrand().'%')
                    ;  
        }

        if ($vehicleFilter->getModel()) {
            $vehicles = $vehicles
                    ->andWhere('v.model LIKE :model')
                    ->setParameter('model', '%'.$vehicleFilter->getModel().'%')
                    ;  
        }

        if ($vehicleFilter->getSeatNumber()) {
            $vehicles = $vehicles
                    ->andWhere('v.seatNumber >= :seat')
                    ->setParameter('seat', $vehicleFilter->getSeatNumber())
                    ;  
        }

        if ($vehicleFilter->getMaxSpeed()) {
            $vehicles = $vehicles
                    ->andWhere('v.maxSpeed >= :speed')
                    ->setParameter('speed', $vehicleFilter->getMaxSpeed())
                    ;  
        }

        if ($vehicleFilter->getWeight()) {
            $vehicles = $vehicles
                    ->andWhere('v.weight <= :weight')
                    ->setParameter('weight', $vehicleFilter->getWeight())
                    ;  
        }

        if ($vehicleFilter->getPower()) {
            $vehicles = $vehicles
                    ->andWhere('v.power >= :power')
                    ->setParameter('power', $vehicleFilter->getPower())
                    ;  
        }

        if ($vehicleFilter->getAutonomy()) {
            $vehicles = $vehicles
                    ->andWhere('v.autonomy >= :autonomy')
                    ->setParameter('autonomy', $vehicleFilter->getAutonomy())
                    ;  
        }

        if ($vehicleFilter->getDayCost()) {
            $vehicles = $vehicles
                    ->andWhere('v.dayCost <= :price')
                    ->setParameter('price', $vehicleFilter->getDayCost())
                    ;  
        }

        if ($vehicleFilter->getType()) {
                $vehicles = $vehicles
                ->andWhere('v.type IN (:type)')
                ->setParameter('type', $vehicleFilter->getType())
                ;  
            }

        return $vehicles->getQuery()->getResult();
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