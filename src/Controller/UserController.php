<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function new(Request $request): Response
    {   

        // creation de l'annonces
        $user = new User();

        $formUser = $this->createForm(UserType::class, $user);

        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            dump($user);
            exit();
        }

        return $this->render('User/inscription.html.twig',[
            'formUser'=>$formUser->createView()
        ]);
    }

    

}

