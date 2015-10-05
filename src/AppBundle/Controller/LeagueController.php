<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;
use AppBundle\Entity\League;

class LeagueController extends Controller
{
    /**
     * @Route("/league/manager", name="league_manager")
     */
    public function managerAction(Request $request)
    {
       $league = new League();
	   
	   $form = $this->createFormBuilder($league)
	   		->add('name','text', array('label'=>'Naslov lige'))
			->add('description','text', array('label'=>'Podrobnosti'))
			->add('save', 'submit', array('label'=>'Dodaj'))
			->getForm();
	   
	   $form->handleRequest($request);

    	if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
			$em->persist($league);
			$em->flush();

        	$this->addFlash('info', 'The league was created successfully.');
        }
		
		$repository = $this->getDoctrine()->getRepository('AppBundle:League');
		$activeLeagues = $repository->findBy(array('active' => true));
		$inactiveLeagues = $repository->findBy(array('active' => false));
	   
        return $this->render('admin/league/manager.html.twig', $params = array(
        	'form' => $form->createView(),
        	'activeLeagues' => $activeLeagues,
        	'inactiveLeagues' => $inactiveLeagues,
        ));
       
    }
	
	/**
	 * @Route("/league/activate/{id}")
	 */
	public function activateAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$league = $em -> getRepository('AppBundle:League') -> find($id);

		if (!$league) {
			throw $this -> createNotFoundException('No league found for id ' . $id);
		}

		$league -> setActive(true);
		$em -> flush();

		return $this -> redirectToRoute('league_manager');
	}
	
	/**
	 * @Route("/league/deactivate/{id}")
	 */
	public function deactivateAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$league = $em -> getRepository('AppBundle:League') -> find($id);

		if (!$league) {
			throw $this -> createNotFoundException('No league found for id ' . $id);
		}

		$league -> setActive(false);
		$em -> flush();

		return $this -> redirectToRoute('league_manager');
	}

	/**
	 * @Route("/league/remove/{id}")
	 */
	public function removeAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$league = $em -> getRepository('AppBundle:League') -> find($id);

		if (!$league) {
			throw $this -> createNotFoundException('No league found for id ' . $id);
		}

		$em->remove($league);
		$em -> flush();

		return $this -> redirectToRoute('league_manager');
	}
	
	/**
	 * @Route("/league/table", name="league_table")
	 */
	public function tableAction(Request $request) {
		$teamRepository = $this->getDoctrine() ->getRepository('AppBundle:Team');
		$allTeams = $teamRepository->findBy(array(),array('points' => 'DESC'));
		
		//TODO: sort by points & group by league
		return $this -> render('common/league/table.html.twig', $params = array('teams' => $allTeams, ));

	}
	
	/**
	 * @Route("/league/nostradamus/table", name="nostradamus_table")
	 */
	public function nostradamusTableAction(Request $request) {
		$nostradamusRepository = $this->getDoctrine() ->getRepository('AppBundle:Nostradamus');
		$all = $nostradamusRepository->findAll();
		
		//TODO: sort by points & group by league
		return $this -> render('common/nostradamus/table.html.twig', $params = array('nostradamuses' => $all, ));

	}
}

