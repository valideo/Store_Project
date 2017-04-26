<?php 

namespace StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller{

	public function menuAction()
	{
		$requestStack = $this->get( 'request_stack');
		$masterRequest = $requestStack->getMasterRequest();

		$route= null;
		if($masterRequest)
		{
			$route = $masterRequest->attributes->get('_route');
		}

		return $this->render('StoreBundle:Main:menu.html.twig', array('activeRoute' => $route));
	}

}
