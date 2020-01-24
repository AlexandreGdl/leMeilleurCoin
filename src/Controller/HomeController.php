<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class HomeController extends AbstractController{
    
    /**
     * @Route("",name="home_index",methods={"GET"})
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {   
        
        return $this->render('Home/index.html.twig');
    }

    /**
     * @Route("cgu",name="home_cgu",methods={"GET"})
     *z
     * @param Request $requestz
     * @return Response
     */
    public function cgu(Request $request): Response
    {

        return $this->render('Home/cgu.html.twig');
    }

}

