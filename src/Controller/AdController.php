<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        // creation de l'annonces
        $ad = new Ad();

        // création du formulaire
        $formAd = $this->createForm(AdType::class, $ad);

        // vérification du formulaire
        $formAd->handleRequest($request);
        if ($formAd->isSubmitted() && $formAd->isValid() && $request->getSession()->get('identifiant')) {

            $ad->setDatecreated(new\Datetime('now'));
            $user = $entityManager->getRepository('App:User')->find($request->getSession()->get('id'));
            $ad->setUser($user);
            $id = $request->getSession()->get('id');
            $entityManager->persist($ad);
            $entityManager->flush();

            // Création d'un message flash
            $this->addFlash("success", "Votre annonce a bien été créée !");
            return $this->redirect('profile/'.$id);
        }
        // appel de la vue
        return $this->render('Ad/new.html.twig',[
            'formAd'=>$formAd->createView()
        ]);
    }

    /**
     * @Route("/rechercher",name="ad_search",methods={"GET", "POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        // creation de l'annonces
        $search = new Ad();

        // création du formulaire
        $formSearch = $this->createForm(SearchFormType::class, $search, ["validation_groups" => ["search"]]);

        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $title = $search->getTitle();
            $zip = $search->getZip();
            $price = $search->getPrice();
            $category = $search->getCategories();
            $searchAd = $entityManager->getRepository("App:Ad")->SearchAd($title, $zip, $price,$category);
            // appel de la vue
            return $this->render('Ad/results.html.twig', [
                'formSearch'=>$formSearch->createView(),
                'annonces' => $searchAd
            ]);
        }
        // appel de la vue
        return $this->render('Ad/search.html.twig',[
            'formSearch'=>$formSearch->createView(),
            'annonces' => $search
        ]);
    }

    /**
     * @Route("/annonces",name="ad_list",methods={"GET"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $ad = $entityManager->getRepository('App:Ad')->findAll();
        return $this->render('Ad/list.html.twig', ['annonces'=>$ad]);
    }

    /**
     * @Route("/annonces/{id}", requirements={"id"="\d+"}, name="ad_detail",methods={"GET"})
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function detail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adId = $request->get('id');
        $ad = $entityManager->getRepository('App:Ad')->find($adId);
        $userId = $request->getSession()->get('id');
        $exist = false;
        $userAd = $ad->getUser();
        // si l'utilisateur est connecter
        if($userId){
            $user = $entityManager->getRepository('App:User')->find($userId);
            foreach($user->getFav() as $annonce){
                if($annonce->getId() == $adId){
                    $exist = true;
                }
            }
            
        }
        return $this->render('Ad/detail.html.twig', [
            'annonce'=>$ad,
            'exist'=>$exist,
            'userAd'=>$userAd
        ]);
    }


    /**
     * @Route("/remove/ad/{id}", requirements={"id"="\d+"},name="ad_remove",methods={"GET"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function remove(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $userId = $request->getSession()->get('id');
        $id = $request->get('id');
        $path = explode('8000',$request->headers->get('referer'));
        $message = "";
        if ($userId){
            $user = $entityManager->getRepository('App:User')->find($userId);
            $ad = $entityManager->getRepository('App:Ad')->find($id);
            if ($ad){
                $adOwner = $ad->getUser();
                if ($adOwner == $user){
                    $entityManager->remove($ad);
                    $entityManager->flush();
                    $this->addFlash("success","Annonces supprimé !");
                    return $this->redirect($path[1]);
                } else { $message = "Cette annonce ne vous appartiens pas !"; }
            } else { $message = "Cette annonce n'existe pas/plus."; }
        } else { $message = "Vous devez etre connecté."; }

        return $this->render('error.html.twig',[
            'message'=>$message
        ]);
    }
}

