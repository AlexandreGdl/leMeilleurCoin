<?php

namespace App\Controller;

use App\Entity\Ad;
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

        $formUser = $this->createForm(UserType::class, $user , ["validation_groups" => ["registration"]]);

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

        $formUser = $this->createForm(UserConnexionType::class, $user, ["validation_groups" => ["connexion"]]);

        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()){
            $exist = $entityManager->getRepository('App:User')->connexion($user->getEmail(),$user->getPassword());
            
            if ($exist){
                $request->getSession()->set('identifiant',$exist[0]['username']);
                $request->getSession()->set('email',$exist[0]['email']);
                $request->getSession()->set('id',$exist[0]['id']);
                return $this->redirect('/');
            } else {
                $this->addFlash("danger","Ce compte n'existe pas !");
            }
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

    /**
     * @Route("/profile/annonces",name="user_annonces",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function annonces(Request $request, EntityManagerInterface $entityManager): Response
    {       
        $user = $entityManager->getRepository('App:User')->find($request->getSession()->get('id'));

        return $this->render('User/annonces.html.twig',[
            "user"=> $user
        ]);
    }

    /**
     * @Route("/profile/fav",name="user_getFav",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function getFav(Request $request, EntityManagerInterface $entityManager): Response
    {       
        $user = $entityManager->getRepository('App:User')->find($request->getSession()->get('id'));

        return $this->render('User/fav.html.twig',[
            "user"=> $user
        ]);
    }


    /**
     * @Route("/fav/{id}",requirements={"id"="\d+"},name="user_addFav",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function addFav(Request $request, EntityManagerInterface $entityManager): Response
    {       
        if($request->getSession()->get('id')){
            $user = $entityManager->getRepository('App:User')->find($request->getSession()->get('id'));
            $id = $request->get('id');
            $ad = $entityManager->getRepository('App:Ad')->find($id);
            $user->addFav($ad);
            $entityManager->persist($user);
            $entityManager->flush();

            $path = explode('8000',$request->headers->get('referer'));

            return $this->redirect($path[1]);
        }



        exit();
    }

    /**
     * @Route("/remove/fav/{id}",requirements={"id"="\d+"},name="user_removeFav",methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */

    public function removeFav(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->getSession()->get('id')){
            $user = $entityManager->getRepository('App:User')->find($request->getSession()->get('id'));
            $id = $request->get('id');
            $ad = $entityManager->getRepository('App:Ad')->find($id);
            $user->removeFav($ad);
            $entityManager->flush();

            return $this->redirectToRoute('user_getFav');
        }
        exit();
    }
}