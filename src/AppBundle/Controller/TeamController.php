<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Team;

class TeamController extends Controller {
	/**
	 * @Route("/team/manager", name="team_manager")
	 */
	public function managerAction(Request $request) {
		$team = new Team();

		$form = $this -> createFormBuilder($team) -> add('name', 'text', array('label' => 'Ime ekipe')) -> add('location', 'text', array('label' => 'Lokacija')) -> add('league', 'entity', array('label' => 'Liga', 'class' => 'AppBundle:League', 'property' => 'Name', 'expanded' => false, 'multiple' => false)) -> add('save', 'submit', array('label' => 'Dodaj')) -> getForm();

		$form -> handleRequest($request);

		if ($form -> isValid()) {
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($team);
			$em -> flush();

			$this -> addFlash('info', 'The item was created successfully.');
		}

		$leagueRepository = $this -> getDoctrine() -> getRepository('AppBundle:League');
		$leagues = $leagueRepository -> findAll();
		$teamRepository = $this -> getDoctrine() -> getRepository('AppBundle:Team');
		$teams = $teamRepository -> findAll();

		return $this -> render('admin/team/manager.html.twig', $params = array('form' => $form -> createView(), 'leagues' => $leagues, 'teams' => $teams, ));

	}

	/**
	 * @Route("/team/remove/{id}")
	 */
	public function removeAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$team = $em -> getRepository('AppBundle:Team') -> find($id);

		if (!$team) {
			throw $this -> createNotFoundException('No team found for id ' . $id);
		}

		$em -> remove($team);
		$em -> flush();

		return $this -> redirectToRoute('team_manager');
	}
	
	/**
	 * @Route("/team/view/{id}")
	 */
	public function viewAction($id) {
		$em = $this -> getDoctrine() -> getManager();
		$team = $em -> getRepository('AppBundle:Team') -> find($id);

		if (!$team) {
			throw $this -> createNotFoundException('No team found for id ' . $id);
		}
		$gameRepository = $this -> getDoctrine() -> getRepository('AppBundle:Game'); 
		$games1 = $gameRepository->findBy(array('finished'=>true, 'homeTeam'=>$id));
		$games2 = $gameRepository->findBy(array('finished'=>true, 'awayTeam'=>$id));
		$finishedGames = array_merge( $games1, $games2 );
		
		return $this -> render('/common/team/view.html.twig', $params = array('team' => $team, 'finishedGames'=>$finishedGames ));
	
	}

}
