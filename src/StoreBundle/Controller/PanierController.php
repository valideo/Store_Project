<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoreBundle\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use StoreBundle\Entity\Commandes;
use UtilisateursBundle\Entity\Utilisateurs;

class PanierController extends Controller
{
    
    /**
     * @Route("/panier", name="show_panier")
    */
    public function showPanier(Request $request)
    {
        $session = $request->getSession();

        if(!$session->has('panier')) $session->set('panier', array());

        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('StoreBundle:Produit')->findArray(array_keys($session->get('panier')));

        return $this->render('StoreBundle:Produits:panier.html.twig', array('produits'=> $produits, 'panier' => $session->get('panier')));
    }


    /**
     * @Route("/add/{id}", name="ajouter")
    */
    public function ajouterPanier($id, Request $request)
    {
        $session = $request->getSession();

        if(!$session->has('panier')) $session->set('panier',array());
        $panier = $session->get('panier');

        if(array_key_exists($id, $panier)){
            if($request->query->get('qte') != null) $panier[$id] = $request->query->get('qte');
        } else {
            if ($request->query->get('qte') != null) {
                $panier[$id] = $request->query->get('qte');
               

            } else

            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('show_panier');
    }


    /**
     * @Route("/del/{id}", name="supprimer")
    */
    public function supprimerPanier($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        
        if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('panier',$panier);
        }
        
        return $this->redirectToRoute('show_panier'); 
    }

    /**
     * @Route("/validation", name="validation")
    */
    public function ValiderPanier()
    {
        
        return $this->render('StoreBundle:Produits:validation.html.twig');
    }


    /**
     * @Route("/checkConnected/{userID}-{pricetot}-{list_produits}", name="checkConnected")
    */
     public function checkConnected(Request $request, $userID, $pricetot, $list_produits)
    {   
        $session = $request->getSession();
        $panier = $session->get('panier');
        $securityContext = $this->container->get('security.authorization_checker');

    if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

        savePanier(1, 1, 1, [1,2]);

        unset($panier);
        $panier = array();
        $session->set('panier',$panier);
        return $this->render('StoreBundle:Produits:validation.html.twig');
        
    }else{

    }
        return $this->redirectToRoute('fos_user_security_login'); 
    }

    public function savePanier($userID, $pricetot, $dateTime, $list_produits )
    {

        $user = $userID;
        $price = $pricetot;
        $date = $dateTime;
        $produits = $list_produits;

        $commande = new Commandes();

        $commande->setProduits($produits);
        $commande->setPrice($price);
        $commande->setDate($date);
        $commande->setUserID($user);


        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

    }

}
