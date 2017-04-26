<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoreBundle\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;

class ProduitsController extends Controller
{
    /**
     * @Route("/produits/create", name="admin_create_produit")
     */
    public function createAction(Request $request)
    {
		$title = $request->query->get('title');
		$type = $request->query->get('type');
		$price = $request->query->get('price');
		$content = $request->query->get('content');
		$img = $request->query->get('img');

    	$produit = new Produit();

    	$produit->setTitle($title);
    	$produit->setType($type);
    	$produit->setPrice($price);
    	$produit->setContent($content);
    	$produit->setImg($img);

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($produit);
    	$em->flush();
        return $this->redirectToRoute('admin_produits');
    }

    /**
     * @Route("/" , name="produits")
     */
    public function listAction()
    {
    		$produits = $this->getDoctrine()->getRepository('StoreBundle:Produit')->findAll();

    		return $this->render('StoreBundle:Produits:list.html.twig', array('produits' => $produits));
    }

	/**
     * @Route("/produit/{id}" , name="detail_produit")
     */
    public function showAction( Produit $produit )
    {
    	return $this->render('StoreBundle:Produits:show.html.twig', array( 'produit' => $produit));
    }

    /**
     * @Route("/admin" , name="admin_produits")
     */
    public function admin_listAction()
    {
    		$produits = $this->getDoctrine()->getRepository('StoreBundle:Produit')->findAll();

    		return $this->render('StoreBundle:Produits:list_admin.html.twig', array('produits' => $produits));
    }

    /**
     * @Route("/admin/delete/{id}" , name="admin_delete_produit")
     */
    public function admin_delete($id)
	{

	    $em = $this->getDoctrine()->getEntityManager();
	    $produit = $em->getRepository('StoreBundle:Produit')->find($id);
	    $em->remove($produit);
	    $em->flush();

	    return $this->redirectToRoute('admin_produits');
	}

}
