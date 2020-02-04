<?php
namespace App\Controller;

use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ad;
use App\Entity\Bid;
use App\Form\AdType;

Class BidController extends AbstractController{
    /**
     * @Route("/ad/bid",name="bid_change",methods={"POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function change(Request $request, EntityManagerInterface $entityManager): Response
    { 
        $price = $request->get('price') ;
        dump($request->get('price'));
        if($this->getUser() && $price){
            if ($price>$this->getUser()->getMoney()){
                print('t povre');
            }
        }
        exit();
    }

}