<?php

namespace App\Controller\User;

use App\Entity\Date;
use App\Entity\Type;
use App\Entity\User;
use App\Form\DateType;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Vehicle;
use App\Form\CommentType;
use App\Form\VehicleType;
use App\Entity\AnnonceStatus;
use App\Service\SluggerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserLeasingController extends AbstractController
{
/**
 * @Route("/user/annonces", name="user_post") 
 */
public function userPost()
{


    return $this->render('user/user_leasing/own_vehicle.html.twig');
}

/**
 * @Route("/user/vehicules", name="user_vehicles") 
 */
public function userVehicleList()
{
    $user = $this->getUser();
    $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findBy([
        'user' => $user,
        'status' => 1
        ]);

    return $this->render('user/user_leasing/user_vehicles.html.twig',[
        'vehicles' => $vehicles
    ]);
}



/**
 * @Route("/user/annonce", name="user_single_annonce" , methods={"POST"}) 
 */
public function userSingleAnnonce(Request $request)
{

    $vehicleId = $request->request->get('vehicleId');

    $user = $this->getUser();

    $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($vehicleId);

    if ($user == $vehicle->getUser()) {

        return $this->render('user/user_leasing/single_annonce.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }
}

/**
 * @Route("/user/annonce/accepter-location", name="user_accept_booking" , methods={"POST"}) 
 */
public function acceptBooking(Request $request, ObjectManager $em,  \Swift_Mailer $mailer)
{
    $token = $request->request->get('token');

    $bookingId = $request->request->get('bookingId') ;

    $bookingRenter =  $this->getDoctrine()->getRepository(Booking::class)->find($bookingId);

    if ($this->isCsrfTokenValid('accept', $token) && $bookingRenter->getOwner() == $this->getUser() ){

        $datesId = preg_split('/-/', $request->request->get('datesId'));

        if($datesId === $bookingRenter->getDateRenter()){

        $bookingRenter->setStatus(2)->setNoticeOwnerStatus(0)->setNoticeRenterStatus(1) ;

        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy([
            'owner' => $this->getUser(),
            'status' => 1
            ]);

           foreach($bookings as $booking){

                if ($booking != $bookingRenter) {

                    foreach($booking->getDateRenter() as $RenterDatesId){
                   
                        if (in_array($RenterDatesId, $datesId)) {
                            $booking->setStatus(4)->setNoticeOwnerStatus(0) ;
                            

                            $message = (new \Swift_Message('Wiwatt : réservation refusée'))
                            ->setFrom($this->getUser()->getEmail())
                            ->setTo($booking->getRenter()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    // templates/emails/registration.html.twig
                                    'emails/refuse_booking.html.twig',
                                    ['booking' => $bookingRenter]
                                ),
                                'text/html'
                            )
                            ;
                    
                            $mailer->send($message);

                            break;
                        }
                   } ;

                }
           }

        foreach( $datesId as $date){

            $em->remove($this->getDoctrine()->getRepository(Date::class)->find($date) ) ;

           

        }
        $em->flush() ;

        $message = (new \Swift_Message('Wiwatt : réservation confirmée'))
        ->setFrom($this->getUser()->getEmail())
        ->setTo($bookingRenter->getRenter()->getEmail())
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/accept_booking.html.twig',
                ['booking' => $bookingRenter]
            ),
            'text/html'
        )
        ;

        $mailer->send($message);


        $this->addFlash('info' , 'Un message a été envoyé à ' . $bookingRenter->getRenter()->getFirstname().' '. $bookingRenter->getRenter()->getLastname() . ' pour lui indiquer que vous avez accepté.');

        return $this->redirectToRoute('owner_historic') ;


        }

        
    }

}


/**
 * @Route("/user/annonce/refuser-location", name="user_refuse_booking" , methods={"POST"}) 
 */
public function denyBooking(Request $request, ObjectManager $em,  \Swift_Mailer $mailer)
{
    $token = $request->request->get('token');

    $bookingId = $request->request->get('bookingId') ;

    $bookingRenter =  $this->getDoctrine()->getRepository(Booking::class)->find($bookingId);

    if ($this->isCsrfTokenValid('deny', $token) && $bookingRenter->getOwner() == $this->getUser() ){

        $bookingRenter->setStatus(3)->setNoticeOwnerStatus(0);

        $em->flush() ;
        
        $message = (new \Swift_Message('Wiwatt : réservation refusée'))
        ->setFrom($this->getUser()->getEmail())
        ->setTo($bookingRenter->getRenter()->getEmail())
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/refuse_booking.html.twig',
                ['booking' => $bookingRenter]
            ),
            'text/html'
        )
        ;

        $mailer->send($message);

        $this->addFlash('success' , 'Demande refusée.') ;

        return $this->redirectToRoute('owner_historic') ;

    }

}

/**
 * @Route("user/vehicule/reception", name="vehicle_reception")
 */

public function vehicleReception(Request $request , ObjectManager $em, \Swift_Mailer $mailer )
{
    $token = $request->request->get('token');

     $bookingId = $request->request->get('bookingId') ;

     $booking =  $this->getDoctrine()->getRepository(Booking::class)->find($bookingId);


    if ($this->isCsrfTokenValid('reception', $token) && $booking->getOwner() == $this->getUser() ){

        
        $booking->setStatus(5) ;

        $em->flush() ;

       
        $message = (new \Swift_Message('Wiwatt : location cloturée'))
        ->setFrom($this->getUser()->getEmail())
        ->setTo($booking->getRenter()->getEmail())
        ->setBody(
            $this->renderView(
                'emails/rent_end.html.twig',
                ['booking' => $booking]
            ),
            'text/html'
        )
        ;

        $mailer->send($message);

        $this->addFlash('success' , 'La location a été cloturée.') ;

        return $this->redirectToRoute('add_review' , ['booking' => $booking->getId() ]) ;

    }

    $this->addFlash('success' , 'Un problème est survenu.') ;

    return $this->redirectToRoute('owner_historic') ;

  
}

/**
 * @Route("user/louer-son-vehicule", name="vehicle_leasing_form")
 */
public function vehicleLeasingForm(Request $request , ObjectManager $em )
{
    $user = $this->getUser();
    $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findBy([
        'user' => $user,
        'status' => 1
        ]);

    $vehicle = new Vehicle;

    $form = $this->createForm(VehicleType::class , $vehicle );

    $form->handleRequest($request);
    
    $token = $request->request->get('token');

    if ($this->isCsrfTokenValid('date-form', $token)){


        $dates = preg_split('/-/' ,$request->request->get('date') ) ;

        $datVehicleId  = $request->request->get('vehicle') ; 

        $datVehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($datVehicleId);
        
        foreach( $dates as $date ){
            $newDate = new Date ;
            


            $dateEurope = preg_replace('#\/#' ,'-' , $date) ;                
            $newDate->setAvailableDate(new \Datetime($dateEurope)) ;
            $newDate->setVehicle( $datVehicle) ;                   
            $em->persist($newDate) ;            
        }

        $em->flush() ;

        $this->addFlash('info' , 'L\' annonce pour votre '. $datVehicle->getType() . ' à bien été créée.');

        return $this->redirectToRoute('show_home') ;
    }

    if ($form->isSubmitted() && $form->isValid()) {
        
        $zipcode =  $request->request->get('zipcode') ;
        $city =  $request->request->get('city') ;
        $longitude =  $request->request->get('longitude') ;
        $latitude =  $request->request->get('latitude') ;

        if (!$latitude || !$longitude ){

            $form = $this->createForm(VehicleType::class , $vehicle );

            $this->addFlash('danger' , 'Veuillez selectionner une adresse valide') ;

            return $this->redirectToRoute('vehicle_leasing_form') ;
        }

        $vehicle->setZipcode($zipcode);
        $vehicle->setCity($city);
        $vehicle->setLongitude($longitude);
        $vehicle->setLatitude($latitude);


        $vehicle->setUser($user);

    // modération insulte
    // a faire en service
    $insultes = array("shit", "con", "connard");
        
    $tableau = explode(' ', $vehicle->getDescription());
    
    foreach($tableau as $mot)
    {
    if (in_array($mot, $insultes))
    {
        $this->addFlash('danger' , 'Le mot '.$mot.' n\'est pas autorisé. Merci de resaisir votre annonce.') ;
        return $this->redirectToRoute('vehicle_leasing_form') ; 
    }
    // fin modération

    }

        $picture = new Picture ;
        $file = $form['picture']->getData();

        // si le fichier n 'est pas vide ( si une photo est envoyée)
        if ( !empty($file) ){
        $someNewFilename = $file->getClientOriginalName() ;          
        $file->move('pictures', $someNewFilename);

        $picture->setImage($file->getClientOriginalName()) ;
        $picture->setVehicle($vehicle) ;
        $em->persist($picture);

        }
        
        $em->persist($vehicle);
        
        $em->flush();

        $this->addFlash('success' , 'Le véhicule à été ajouté à votre liste.') ;
        
        return $this->redirectToRoute('vehicle_leasing_form') ;
    }

    return $this->render('user/user_leasing/leasing_vehicle.html.twig', [
        'vehicleForm' => $form->createView(),
        'vehicles' => $vehicles,
    ]);
}


/**
 * @Route("/user/annonce/delete", methods={"POST"}, name="annonce_delete" ) 
*/
public function deletePost(Request $request, ObjectManager $em )
{
    
    $token = $request->request->get('token');
   
    if ($this->isCsrfTokenValid('delete-item', $token)){

        $id = $request->request->get('vehicle_id');
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id); 

        $user = $this->getUser() ;

        if ( $vehicle->getUser() == $user){

            $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy([
                'owner' => $this->getUser(),
                'status' => 1
                ]);

            foreach($bookings as $booking ){

                $booking->setStatus(4) ;
            }

            foreach( $vehicle->getDates() as  $date ){

                $em->remove($date) ;
     
             }
     
             
             $em->flush();
     
             $this->addFlash('success', 'Votre annonce a été supprimée');
             return $this->redirectToRoute('user_post');

        }
    }

    $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette annonce.');
    return $this->redirectToRoute('user_post'); 
} 


/**
 * @Route("/user/renter/locations", name="user_rental") 
 */
public function showRental(Request $request, PaginatorInterface $paginator)
{
    $bookings = $this->getDoctrine()->getRepository(Booking::class)->findByRenter($this->getUser());

    $pagination = $paginator->paginate(
        $bookings, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        5 /*limit per page*/
    );

    return $this->render('user/user_leasing/vehicle_rent.html.twig', [
        'bookings' => $pagination,
    ]);
}

/**
 * @Route("/user/owner/locations", name="owner_historic") 
 */
public function showOwnerHistoric(Request $request, PaginatorInterface $paginator)
{

    $bookings = $this->getDoctrine()->getRepository(Booking::class)->findByOwner($this->getUser());

    $pagination = $paginator->paginate(
        $bookings, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        5 /*limit per page*/
    );

    return $this->render('user/user_leasing/owner_historic.html.twig', [
        'bookings' => $pagination,
        ]);
}


/**
 * @Route("/user/{booking}/avis", name="add_review")
 */
public function addReview(Request $request , ObjectManager $em  , Booking $booking, \Swift_Mailer $mailer)
{
    $comment = new Comment;

    $form = $this->createForm(CommentType::class, $comment);
             
    $form->handleRequest($request);
        
 if  ( $booking->getOwner() == $this->getUser() || $booking->getRenter() == $this->getUser()) {



                if ($form->isSubmitted() && $form->isValid()) {

                        $content = $request->request->get('content') ;
                        $score = $request->request->get('score');
                        


                        if ( $booking->getOwner() == $this->getUser()){

                            $comment->setCustomer( $booking->getRenter() );
                            $comment->setOwnerComment($booking);
                        }

                        if ( $booking->getRenter() == $this->getUser()){

                            $comment->setCustomer( $booking->getOwner() ); 
                            $comment->setRenterComment($booking);
                        }

                        $comment->setAuthor( $this->getUser() );
    
                        $em->persist($comment);
                        
                        $em->flush();

                        $allRate = $this->getDoctrine()->getRepository(Comment::class)->findBy(['customer' => $comment->getCustomer()]) ;
                        

                        $totalRate = 0 ;

                        foreach($allRate as $rate){

                            $totalRate += $rate->getScore() ;

                        }
                        $comment->getCustomer()->setAverageScore($totalRate/count($allRate)) ;
                        

                        $em->flush() ;

                        if ( $booking->getOwner() == $this->getUser()){

                        $message = (new \Swift_Message('Wiwatt : Vous avez un nouveau commentaire'))
                        ->setFrom($this->getUser()->getEmail())
                        ->setTo($booking->getRenter()->getEmail())
                        ->setBody(
                            $this->renderView(
                                'emails/new_review_renter.html.twig',
                                ['booking' => $booking,
                                'comment'  => $comment,
                                'score'   => $comment->getScore(),
                                ]
                            ),
                            'text/html'
                        )
                        ;
                
                        $mailer->send($message);
                        }

                        if ( $booking->getRenter() == $this->getUser()){
                            $message = (new \Swift_Message('Wiwatt : Vous avez un nouveau commentaire'))
                            ->setFrom($this->getUser()->getEmail())
                            ->setTo($booking->getOwner()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'emails/new_review_owner.html.twig',
                                    ['booking' => $booking,
                                    'comment'  => $comment,
                                    'score'   => $comment->getScore(),
                                    ]
                                ),
                                'text/html'
                            )
                            ;
                    
                            $mailer->send($message);
                        }


                        $this->addFlash('info' , 'Le commentaire a bien été ajouté.') ;
                        
                        return $this->redirectToRoute('owner_historic') ;

                   
                    } 

                     
            
            return $this->render('user/user_leasing/add_review.html.twig', [
                'form' => $form->createView()
            ]);

         
    }

    return dump('404');exit ;

}


    /**
     * @Route("/user/vehicules/delete", methods={"POST"}, name="vehicle_delete" ) 
    */
    public function deleteVehicle(Request $request, ObjectManager $em )
    {
        $user = $this->getUser(); 

        $token = $request->request->get('token');

        $vehicleId = $request->request->get('vehicle_id');

        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($vehicleId);


        if ($this->isCsrfTokenValid('delete-vehicle', $token) && $user == $vehicle->getUser()){
        
            $bookings = $this->getDoctrine()->getRepository(Booking::class)->findBy(['vehicle' => $vehicle]);


            if ($bookings) {
                foreach($bookings as $booking) {

                    if($booking->getStatus() != 2 ){
                
                        $vehicle->setStatus(0);
                  
                        $dates = $this->getDoctrine()->getRepository(Date::class)->findBy(['vehicle' => $vehicle]);
            
                        foreach ($dates as $date ){
                            $em->remove($date);
                        }
            
                        $em->persist($vehicle);
                        $em->flush();
            
                        $this->addFlash('success', 'Votre vehicule a été supprimé.');
                        return $this->redirectToRoute('user_vehicles');
                        
                    } else {
                        $this->addFlash('danger', 'Une location est en cours, suppression impossible.');
                        return $this->redirectToRoute('user_vehicles');
                    }
                }
            } else {
                $vehicle->setStatus(0);
                  
                $dates = $this->getDoctrine()->getRepository(Date::class)->findBy(['vehicle' => $vehicle]);
    
                foreach ($dates as $date ){
                    $em->remove($date);
                }
    
                $em->persist($vehicle);
                $em->flush();
    
                $this->addFlash('success', 'Votre vehicule a été supprimé.');
                return $this->redirectToRoute('user_vehicles');
            }
        }

        $this->addFlash('danger', 'Une erreur est survenue.');
        return $this->redirectToRoute('user_vehicles'); 
    } 


}
