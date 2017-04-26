<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoreBundle\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;

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
        $session = $session = $request->getSession();
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

}
