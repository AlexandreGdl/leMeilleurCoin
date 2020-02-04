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

            $path = explode('8000',$request->headers->get('referer'));

            return $this->redirect($path[1]);
        }
        exit();
    }

    /**
     * @Route("/profile/{id}",requirements={"id"="\d+"},name="user_profile",methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */

    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository('App:User')->find($request->get('id'));
        $id = $request->get('id');
        $idSession = $request->getSession()->get('id');
        $sessionProfile = false;
        if ($idSession){
            if($id == $idSession ){
                $sessionProfile = true;
            }
        }
       
        return $this->render('User/profile.html.twig',[
            'user'=>$user,
            'sessionProfile' => $sessionProfile
        ]);
    }
}