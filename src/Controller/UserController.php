<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;

Class UserController extends AbstractController{
    
    /**
     * @Route("/inscription",name="user_new",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   

        // creation de l'annonces
        $user = new User();

        $formUser = $this->createForm(UserType::class, $user);

        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){

            $user->setDateregistered(new \Datetime('now'));
            $user->setRoles('user');
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("success","Utilisateur Inscrit !");

        } else if ($formUser->isSubmitted()){
            $this->addFlash("danger","Verifier votre formulaire");
        }

        return $this->render('User/inscription.html.twig',[
            'formUser'=>$formUser->createView()
        ]);
    }

    

}

