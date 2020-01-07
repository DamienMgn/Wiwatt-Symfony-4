<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\Booking;
use App\Entity\Vehicle;
use App\Service\Search;
use App\Entity\BookingStatus;
use App\Entity\SearchFilter;
use App\Form\SearchFilterType;
use App\Repository\DateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LeasingController extends AbstractController
{
    
    /**
     * @Route("/locations", name="leasing_index")
     */
    public function index(Request $request, Search $search)
    {

        $longitude = $request->query->get('longitude');
        $latitude = $request->query->get('latitude');
        $dates = $request->query->get('date');
        $token = $request->query->get('token');

        $vehicles = [];

        $vehicleFilter = new SearchFilter;

        $form = $this->createForm(SearchFilterType::class, $vehicleFilter);
        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $vehicles = $search->search($dates, $longitude, $latitude, $vehicleFilter);
            }

            if ($this->isCsrfTokenValid('date-form', $token)) {
                $vehicles = $search->search($dates, $longitude, $latitude, $vehicleFilter);
            }
    
        unset($vehicleFilter);
        unset($form);
        $vehicleFilter = new SearchFilter();
        $form = $this->createForm(SearchFilterType::class, $vehicleFilter);

        return $this->render('leasing/index.html.twig', [
            'vehicles' => $vehicles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/location/{id}/voir-annonce", name="show_leasing")
     */
    public function showLocation(Request $request , Vehicle $vehicle , ObjectManager $em,  \Swift_Mailer $mailer)
    {
        $dates = $this->getDoctrine()->getRepository(Date::class)->getAvailableDates( $vehicle ) ;

        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('booking-ask', $token)) {

            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $arrayDatesID = $request->request->get('inputDate') ;

            $arrayDates = [];

            if ($arrayDatesID == null) {

                $this->addFlash('danger' , 'Veuillez choisir des dates.');

                return $this->redirect($request->getUri());
            }

            foreach($arrayDatesID as $dateId){

                $date = $this->getDoctrine()->getRepository(Date::class)->find($dateId);

                $dateAvailable = $date->getAvailableDate()->format('m/d/Y');

                $arrayDates[]  = $dateAvailable;
            }

            if ($this->getUser() != $vehicle->getUser() ){

            $booking = new Booking ;

            $booking->setDate($arrayDates);
            $booking->setVehicle($vehicle) ;            
            $booking->setOwner( $vehicle->getUser());
            $booking->setRenter($this->getUser());
            $booking->setStatus(1);
            $booking->setDateRenter($arrayDatesID) ;
            $booking->setNoticeRenterStatus(0);

            $em->persist($booking);
            $em->flush() ;

            $message = (new \Swift_Message('Wiwatt : Demande de Réservation'))
            ->setFrom($this->getUser()->getEmail())
            ->setTo($vehicle->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/ask_booking.html.twig',
                    ['userRenter' => $this->getUser(), 
                    'userOwner' => $vehicle->getUser(),
                    'vehicle' => $vehicle,
                    'booking' => $booking,
                    ]
                ),
                'text/html'
            )
            ;
    
            $mailer->send($message);

            $this->addFlash('success' , 'Demande envoyée à '. $vehicle->getUser()->getFirstname(). ' ' . $vehicle->getUser()->getLastname().'.' ) ;

            return $this->redirect($request->getUri());

            } else if ( $this->getUser() == $vehicle->getUser() ){

                $this->addFlash('danger' , 'Vous ne pouvez pas louer votre propre véhicule') ; 

            }

        }

        return $this->render('leasing/show_leasing.html.twig',[
            'vehicle' => $vehicle,
            'dates' => $dates            
        ]);
        
    }





}
