<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
           $this->redirectToRoute('show_home');
        }

       
       $error = $authenticationUtils->getLastAuthenticationError();
       
       $lastUsername = $authenticationUtils->getLastUsername();

       return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        
    }




    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {       
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/inscription", name="subscribe")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder , AuthenticationUtils $authenticationUtils){

        $user = new User;
        $form = $this->createForm(RegisterType::class , $user);
        $error = $authenticationUtils->getLastAuthenticationError();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            
            
                $user->setUsername($user->getEmail());
                $plainPassword = $user->getPassword();
                $encodedPassword = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Bonjour '. $user->getFirstname() .' votre compte à été créé avec succès. Vous pouvez maintenant vous connecter.');
                return $this->redirectToRoute('app_login');

            } 
        

        return $this->render('security/subscribe.html.twig', [
            'registerForm' => $form->createView() ,
            'error' => $error,
        ]);
    }
}
