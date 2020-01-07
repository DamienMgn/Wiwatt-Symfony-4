<?php

namespace App\Service;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Search
{

    private $vehicles = [];

    private $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function search($dates, $longitude, $latitude, $vehicleFilter)
    {

        if (!empty($dates)) {
            $arrayDates = preg_split('/-/', $dates);
            $arrayDatesTime = [];
    
            foreach($arrayDates as $date) {
                $dateReplace = str_replace("/", "-", $date);
                $arrayDatesTime[] = new \DateTime($dateReplace);
            } 
            
            $em = $this->container->get('doctrine')->getEntityManager();

            return $this->vehicles = $em->getRepository(Vehicle::class)->findByDistancesAndDates($longitude, $latitude, $arrayDatesTime, $vehicleFilter);
            
        } else {
            
            $em = $this->container->get('doctrine')->getEntityManager();
            
            return $this->vehicles = $em->getRepository(Vehicle::class)->findByDistances($longitude, $latitude, $vehicleFilter);
            
        }
       
    }
}