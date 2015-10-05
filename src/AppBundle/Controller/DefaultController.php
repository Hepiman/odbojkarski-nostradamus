<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // $krka = new Team();
// 		
		// $krka->setName('MOK Krka');
		// $krka->setDescription('Novo mesto');
// 		
		// $em = $this->getDoctrine()->getManager();
		// $em->persist($krka);
		// $em->flush();
		// $id=1;
		// $teams = $this->getDoctrine()->getRepository('AppBundle:Team')->findAll();
		// if(!$teams){
			// throw $this->createNotFoundException('No product found for id'.$id);
		// }
		
        return $this->render('default/index2.html.twig', $params = array('teams' => null, ));
       
    }
}
