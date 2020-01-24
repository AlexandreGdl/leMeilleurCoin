<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserConnexionType;

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
            $exist = $entityManager->getRepository('App:User')->checkEmailUsed($user->getEmail());
            if ($exist){
                $this->addFlash("danger","Email déja pris !");
            } else {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success","Utilisateur Inscrit !");
            }


        } else if ($formUser->isSubmitted()){
            $this->addFlash("danger","Verifier votre formulaire");
        }

        return $this->render('User/inscription.html.twig',[
            'formUser'=>$formUser->createView()
        ]);
    }

    /**
     * @Route("/connexion",name="user_connexion",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function connexion(Request $request, EntityManagerInterface $entityManager): Response
    {       

        $user = new User();

        $formUser = $this->createForm(UserConnexionType::class, $user);

        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            $exist = $entityManager->getRepository('App:User')->connexion($user->getEmail(),$user->getPassword());
            
            $request->getSession()->set('identifiant',$exist[0]['username']);
            $request->getSession()->set('email',$exist[0]['email']);
            $request->getSession()->set('id',$exist[0]['id']);
            return $this->redirect('/');
        }
        return $this->render('User/connexion.html.twig',[
            'formUser'=>$formUser->createView()
        ]);
    }

    /**
     * @Route("/all",name="user_all",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function all(Request $request, EntityManagerInterface $entityManager): Response
    {       
            $userGet = $entityManager->getRepository('App:User')->getAllUser();
           return $this->render('User/all.html.twig',['user'=>$userGet]);
    }

    /**
     * @Route("/disconnect",name="user_disconnect",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function disconnect(Request $request, EntityManagerInterface $entityManager): Response
    {       
        $request->getSession()->set('identifiant',null);
            $request->getSession()->set('email',null);
            $request->getSession()->set('id',null);
           return $this->redirect('/');
    }

}

