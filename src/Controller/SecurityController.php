<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use App\Form\UserConnexionType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\LoginAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(Request $request,AuthenticationUtils $authenticationUtils,EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            return $this->redirect('/');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('Security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home_index');
        //throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        // creation de l'annonces
        $user = new User();

        $formUser = $this->createForm(UserType::class, $user , ["validation_groups" => ["registration"]]);

        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            
            //Encode le mot de passe 

            $password = $encoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setDateregistered(new \Datetime('now'));

            $exist = $entityManager->getRepository('App:User')->checkEmailUsed($user->getEmail());
            if ($exist){
                $this->addFlash("danger","Email déja pris !");
            } else {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success","Utilisateur Inscrit !");
                return $this->redirectToRoute('app_login');
            }


        } else if ($formUser->isSubmitted()){
            $this->addFlash("danger","Verifier votre formulaire");
        }

        return $this->render('User/inscription.html.twig',[
            'formUser'=>$formUser->createView()
        ]);
    }
}
