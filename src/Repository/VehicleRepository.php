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

    public function findByDistances($longitude, $latitude, SearchFilter $vehicleFilter)
    {

        $vehicles = $this->vehicleFilter($vehicleFilter);

        $distancesVehicles = [] ;

        foreach($vehicles as $vehicle){


         $distance =  self::distance($longitude, $latitude , $vehicle->getLongitude() ,$vehicle->getLatitude() , $unit = 'k' ) ;

         if ($distance <= 20) {
            $distancesVehicles[] = $vehicle;
         }

        }

        $VehiclesMatch = [];

        foreach ($distancesVehicles as $vehicle) {

            foreach ($vehicle->getDates() as $date) {
                if ($date->getAvailableDate() >= $this->dateTime) {
                    $VehiclesMatch[] = $vehicle;
                    break;
                }
            }

        }
        
        return $VehiclesMatch;

    }

    /**
    * @return Vehicle[] Returns an array of Vehicle objects
    */

    public function findByDistancesAndDates($longitude, $latitude, $datesUser, SearchFilter $vehicleFilter)
    {

        $vehicles = $this->vehicleFilter($vehicleFilter);

        $distancesVehicles = [] ;

        foreach($vehicles as $vehicle){


         $distance =  self::distance($longitude, $latitude , $vehicle->getLongitude() ,$vehicle->getLatitude() , $unit = 'k' ) ;

         if ($distance <= 20) {
            $distancesVehicles[] = $vehicle;
         }

        }
        
        $vehiclesMatch = [];

        // Get Dates by vehicles
        foreach ($distancesVehicles as $vehicle) {

            $datesMatch = [];

            foreach ($vehicle->getDates() as $date) {
                if (true) {
                    $datesMatch[] = [$date->getAvailableDate()];
                }
            }

            $datesArray = [];

            foreach ($datesMatch as $date) {
                $datesArray[] = $date[0];
            }

            $vehicleMatch = false;

            // Check if userDates match with véhicle dates
            foreach ($datesUser as $dateUser) {
                if (in_array($dateUser, $datesArray)) {
                    $vehicleMatch = true;
                } else {
                    $vehicleMatch = false;
                    break;
                }
            }

            if ($vehicleMatch) {
                $vehiclesMatch[] = $vehicle;
            } 
        }

        return $vehiclesMatch ;

    }

    public static function distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        //
        $meter = ($earth_radius * $d);
        if ($unit == 'k') {
            return $meter / 1000;
        }
    }


    public function vehicleFilter($vehicleFilter) {
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