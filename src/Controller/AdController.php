<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Form\AdType;

Class AdController extends AbstractController{
    
    /**
     * @Route("/deposer",name="ad_new",methods={"GET","POST"})
     * 
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {   
        // creation de l'annonces
        $ad = new Ad();

        $formAd = $this->createForm(AdType::class, $ad);
        return $this->render('Ad/new.html.twig',[
            // 'formAd'=>$formAd->createView()
        ]);
    }

    /**
     * @Route("/rechercher",name="ad_search",methods={"GET"})
     * 
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {   
        
        return $this->render('Ad/search.html.twig');
    }

    /**
     * @Route("/annonces",name="ad_list",methods={"GET"})
     * 
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {   
        
        return $this->render('Ad/list.html.twig');
    }

}

