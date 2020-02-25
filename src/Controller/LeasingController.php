<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\Booking;
use App\Entity\Vehicle;
use App\Service\SearchVehicles;
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
     * @Route("/annonces", name="leasing_index")
     */
    public function index(Request $request, SearchVehicles $searchVehicles)
    {

        $city = $request->query->get('city');
        $token = $request->query->get('token');
        $dates = $request->query->get('date');

        $vehicles = [];

        $searchFilter = new SearchFilter;

        $form = $this->createForm(SearchFilterType::class, $searchFilter);

        $form->handleRequest($request);

        // Envoi du formulaire depuis la Home

        if ($this->isCsrfTokenValid('date-form', $token)) {
            $vehicles = $searchVehicles->searchVehicles($dates, $city, $searchFilter);
        }

        // Envoi du formulaire depuis la page Annonces

        if($form->isSubmitted() && $form->isValid()) {

            $vehicles = $searchVehicles->searchVehicles($dates, $city, $searchFilter);

            // Destruction des variables pour vider les inputs

            unset($searchFilter);
            unset($form);
            
            $searchFilter = new SearchFilter();
            $form = $this->createForm(SearchFilterType::class, $searchFilter);
        }
    
        return $this->render('leasing/index.html.twig', [
            'vehicles' => $vehicles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{id}/voir-annonce", name="show_leasing")
     */
    public function showLocation(Request $request , Vehicle $vehicle , ObjectManager $em,  \Swift_Mailer $mailer)
    {
        $dates = $this->getDoctrine()->getRepository(Date::class)->getAvailableDates( $vehicle );

        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('booking-ask', $token)) {

            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $arrayDatesID = $request->request->get('inputDate');

            if ($arrayDatesID == null) {
                $this->addFlash('danger' , 'Veuillez choisir des dates.');
                return $this->redirect($request->getUri());
            }

            $arrayDates = [];

            foreach($arrayDatesID as $dateId){
                $date = $this->getDoctrine()->getRepository(Date::class)->find($dateId);
                    if ($date->getVehicle() === $vehicle) {
                        $arrayDates[] = $date->getAvailableDate()->format('m/d/Y');
                    } else {
                        $this->addFlash('danger' , 'Les dates ne correspondent pas');
                        return $this->redirect($request->getUri());
                    }
            }

            if ($this->getUser() != $vehicle->getUser() ){

                $booking = new Booking ;

                $booking->setDate($arrayDates);
                $booking->setVehicle($vehicle) ;            
                $booking->setOwner( $vehicle->getUser());
                $booking->setRenter($this->getUser());
                $booking->setStatus(1);
                $booking->setDateRenter($arrayDatesID);
                $booking->setNoticeRenterStatus(0);

                $em->persist($booking);
                $em->flush();

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
                );
        
                $mailer->send($message);

                $this->addFlash('success' , 'Demande envoyée à '. $vehicle->getUser()->getFirstname(). ' ' . $vehicle->getUser()->getLastname().'.' );

                return $this->redirect($request->getUri());

            } else if ( $this->getUser() == $vehicle->getUser() ){
                $this->addFlash('danger' , 'Vous ne pouvez pas louer votre propre véhicule'); 
            }
        }

        return $this->render('leasing/show_leasing.html.twig',[
            'vehicle' => $vehicle,
            'dates' => $dates            
        ]);
    }
}
