<?php

namespace App\Service;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;

class SearchVehicles
{

    private $vehicles = [];

    private $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->dateTime = new \DateTime();
    }

    public function searchVehicles($dates, $city, $vehicleFilter)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $vehiclesByFilter = $em->getRepository(Vehicle::class)->findByFilters($vehicleFilter);

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=' . $city);

        $content = $response->toArray()['features'][0]['geometry']['coordinates'];

        $longitude = $content[0];
        $latitude = $content[1];

        $vehiclesMatch = [];

        foreach($vehiclesByFilter as $vehicle) {

            $distance =  self::distance($longitude, $latitude , $vehicle->getLongitude() ,$vehicle->getLatitude() , $unit = 'k' );

            if ($distance <= 20) {
                $vehiclesMatch[] = $vehicle;
            }
        }

        if (!empty($dates)) {
            return $this->vehicles = $this->compareDates($vehiclesMatch, $dates);
        } else {
            return $this->vehicles = $vehiclesMatch;
        }
    }

    // calculer la distance
    private static function distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
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

    // comparer les dates envoyées par l'utilisateur aux dates de disponibilités du véhicule
    private function compareDates($vehicles, $dates) {

        $arrayDates = preg_split('/-/', $dates);
        $arrayDatesTime = [];

        foreach($arrayDates as $date) {
            $dateReplace = str_replace("/", "-", $date);
            $arrayDatesTime[] = new \DateTime($dateReplace);
        } 

        $vehiclesMatchWithDates = [];

        foreach ($vehicles as $vehicle) {

            $arrayDatesVehicles = [];

            foreach ($vehicle->getDates() as $date) {
                    $arrayDatesVehicles[] = [$date->getAvailableDate()][0];
                }

            $vehicleMatch = false;

            // Check if userDates match with véhicle dates
            foreach ($arrayDatesTime as $dateUser) {
                if (in_array($dateUser, $arrayDatesVehicles)) {
                    $vehicleMatch = true;
                } else {
                    $vehicleMatch = false;
                    break;
                }
            }

            if ($vehicleMatch) {
                $vehiclesMatchWithDates[] = $vehicle;
            }
        }

        return $vehiclesMatchWithDates;
    }
}